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
use function array_chunk;
use function count;
use function max;

final class ShapedRecipe extends RecipeWithTypeId{
	private string $blockName;

	/**
	 * @param RecipeIngredient[][] $input
	 * @param ItemStack[]          $output
	 * @phpstan-param list<list<RecipeIngredient>> $input
	 * @phpstan-param list<ItemStack> $output
	 */
	public function __construct(
		int $typeId,
		private string $recipeId,
		private array $input,
		private array $output,
		private UuidInterface $uuid,
		string $blockType, //TODO: rename this
		private int $priority,
		private bool $symmetric,
		private ?RecipeUnlockingRequirement $unlockingRequirement,
		private int $recipeNetId
	){
		parent::__construct($typeId);
		$rows = count($input);
		if($rows < 1 or $rows > 3){
			throw new \InvalidArgumentException("Expected 1, 2 or 3 input rows");
		}
		$columns = null;
		foreach($input as $rowNumber => $row){
			if($columns === null){
				$columns = count($row);
			}elseif(count($row) !== $columns){
				throw new \InvalidArgumentException("Expected each row to be $columns columns, but have " . count($row) . " in row $rowNumber");
			}
		}
		$this->blockName = $blockType;
	}

	public function getRecipeId() : string{
		return $this->recipeId;
	}

	public function getWidth() : int{
		return count($this->input[0]);
	}

	public function getHeight() : int{
		return count($this->input);
	}

	/**
	 * @return RecipeIngredient[][]
	 * @phpstan-return list<list<RecipeIngredient>>
	 */
	public function getInput() : array{
		return $this->input;
	}

	/**
	 * @return ItemStack[]
	 * @phpstan-return list<ItemStack>
	 */
	public function getOutput() : array{
		return $this->output;
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

	public function isSymmetric() : bool{ return $this->symmetric; }

	public function getUnlockingRequirement() : ?RecipeUnlockingRequirement{ return $this->unlockingRequirement; }

	public function getRecipeNetId() : int{
		return $this->recipeNetId;
	}

	public static function decode(int $recipeType, ByteBufferReader $in) : self{
		$recipeId = CommonTypes::getString($in);
		$width = VarInt::readSignedInt($in);
		$height = VarInt::readSignedInt($in);
		$ingredients = [];
		for($i = 0, $ingredientCount = VarInt::readUnsignedInt($in); $i < $ingredientCount; ++$i){
			$ingredients[] = RecipeIngredient::read($in);
		}
		$input = array_chunk($ingredients, max(1, $width));

		$output = [];
		for($k = 0, $resultCount = VarInt::readUnsignedInt($in); $k < $resultCount; ++$k){
			$output[] = CommonTypes::getItemStackWithoutStackId($in);
		}
		$uuid = CommonTypes::getUUID($in);
		$block = CommonTypes::getString($in);
		$priority = VarInt::readSignedInt($in);
		$symmetric = CommonTypes::getBool($in);
		$unlockingRequirement = CommonTypes::getBool($in) ? RecipeUnlockingRequirement::read($in) : null;

		$recipeNetId = VarInt::readSignedInt($in);

		return new self($recipeType, $recipeId, $input, $output, $uuid, $block, $priority, $symmetric, $unlockingRequirement, $recipeNetId);
	}

	public function encode(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->recipeId);
		VarInt::writeSignedInt($out, $this->getWidth());
		VarInt::writeSignedInt($out, $this->getHeight());
		VarInt::writeUnsignedInt($out, $this->getWidth() * $this->getHeight());
		foreach($this->input as $row){
			foreach($row as $ingredient){
				$ingredient->write($out);
			}
		}

		VarInt::writeUnsignedInt($out, count($this->output));
		foreach($this->output as $item){
			CommonTypes::putItemStackWithoutStackId($out, $item);
		}

		CommonTypes::putUUID($out, $this->uuid);
		CommonTypes::putString($out, $this->blockName);
		VarInt::writeSignedInt($out, $this->priority);
		CommonTypes::putBool($out, $this->symmetric);
		CommonTypes::putBool($out, $this->unlockingRequirement !== null);
		$this->unlockingRequirement?->write($out);

		VarInt::writeSignedInt($out, $this->recipeNetId);
	}
}
