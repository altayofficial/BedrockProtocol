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
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;

final class SmithingTransformRecipe extends RecipeWithTypeId{

	public function __construct(
		int $typeId,
		private string $recipeId,
		private RecipeIngredient $template,
		private RecipeIngredient $input,
		private RecipeIngredient $addition,
		private ItemStack $output,
		private string $blockName,
		private int $recipeNetId
	){
		parent::__construct($typeId);
	}

	public function getRecipeId() : string{ return $this->recipeId; }

	public function getTemplate() : RecipeIngredient{ return $this->template; }

	public function getInput() : RecipeIngredient{ return $this->input; }

	public function getAddition() : RecipeIngredient{ return $this->addition; }

	public function getOutput() : ItemStack{ return $this->output; }

	public function getBlockName() : string{ return $this->blockName; }

	public function getRecipeNetId() : int{ return $this->recipeNetId; }

	public static function decode(int $typeId, ByteBufferReader $in) : self{
		$recipeId = CommonTypes::getString($in);
		$template = RecipeIngredient::read($in);
		$input = RecipeIngredient::read($in);
		$addition = RecipeIngredient::read($in);
		$output = CommonTypes::getItemStackWithoutStackId($in);
		$blockName = CommonTypes::getString($in);
		$recipeNetId = VarInt::readSignedInt($in);

		return new self(
			$typeId,
			$recipeId,
			$template,
			$input,
			$addition,
			$output,
			$blockName,
			$recipeNetId
		);
	}

	public function encode(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->recipeId);
		$this->template->write($out);
		$this->input->write($out);
		$this->addition->write($out);
		CommonTypes::putItemStackWithoutStackId($out, $this->output);
		CommonTypes::putString($out, $this->blockName);
		VarInt::writeSignedInt($out, $this->recipeNetId);
	}
}
