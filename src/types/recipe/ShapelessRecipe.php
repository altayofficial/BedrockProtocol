<?php

/*
 *
 *      _    _ _
 *     / \  | | |_ __ _ _   _
 *    / _ \ | | __/ _` | | | |
 *   / ___ \| | || (_| | |_| |
 *  /_/   \_\_|\__\__,_|\__, |
 *                       |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Original work by the PocketMine Team.
 * https://www.pocketmine.net/
 *
 * @author Altay Team
 * @link https://github.com/altayofficial
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use Ramsey\Uuid\UuidInterface;
use function count;

final class ShapelessRecipe extends RecipeWithTypeId{
	/**
	 * @param RecipeIngredient[] $inputs
	 * @param ItemStack[]        $outputs
	 * @phpstan-param list<RecipeIngredient> $inputs
	 * @phpstan-param list<ItemStack> $outputs
	 */
	public function __construct(
		int $typeId,
		private string $recipeId,
		private array $inputs,
		private array $outputs,
		private UuidInterface $uuid,
		private string $blockName,
		private int $priority,
		private ?RecipeUnlockingRequirement $unlockingRequirement,
		private int $recipeNetId
	){
		parent::__construct($typeId);
	}

	public function getRecipeId() : string{
		return $this->recipeId;
	}

	/**
	 * @return RecipeIngredient[]
	 * @phpstan-return list<RecipeIngredient>
	 */
	public function getInputs() : array{
		return $this->inputs;
	}

	/**
	 * @return ItemStack[]
	 * @phpstan-return list<ItemStack>
	 */
	public function getOutputs() : array{
		return $this->outputs;
	}

	public function getUuid() : UuidInterface{
		return $this->uuid;
	}

	public function getBlockName() : string{
		return $this->blockName;
	}

	public function getPriority() : int{
		return $this->priority;
	}

	public function getUnlockingRequirement() : ?RecipeUnlockingRequirement{ return $this->unlockingRequirement; }

	public function getRecipeNetId() : int{
		return $this->recipeNetId;
	}

	public static function decode(int $recipeType, ByteBufferReader $in) : self{
		$recipeId = CommonTypes::getString($in);
		$input = [];
		for($j = 0, $ingredientCount = VarInt::readUnsignedInt($in); $j < $ingredientCount; ++$j){
			$input[] = RecipeIngredient::read($in);
		}
		$output = [];
		for($k = 0, $resultCount = VarInt::readUnsignedInt($in); $k < $resultCount; ++$k){
			$output[] = CommonTypes::getItemStackWithoutStackId($in);
		}
		$uuid = CommonTypes::getUUID($in);
		$block = CommonTypes::getString($in);
		$priority = VarInt::readSignedInt($in);
		$unlockingRequirement = CommonTypes::getBool($in) ? RecipeUnlockingRequirement::read($in) : null;

		$recipeNetId = VarInt::readSignedInt($in);

		return new self($recipeType, $recipeId, $input, $output, $uuid, $block, $priority, $unlockingRequirement, $recipeNetId);
	}

	public function encode(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->recipeId);
		VarInt::writeUnsignedInt($out, count($this->inputs));
		foreach($this->inputs as $item){
			$item->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->outputs));
		foreach($this->outputs as $item){
			CommonTypes::putItemStackWithoutStackId($out, $item);
		}

		CommonTypes::putUUID($out, $this->uuid);
		CommonTypes::putString($out, $this->blockName);
		VarInt::writeSignedInt($out, $this->priority);
		CommonTypes::putBool($out, $this->unlockingRequirement !== null);
		$this->unlockingRequirement?->write($out);

		VarInt::writeSignedInt($out, $this->recipeNetId);
	}
}
