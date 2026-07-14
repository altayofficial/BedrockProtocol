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

class PotionTypeRecipe{
	public function __construct(
		private int $inputItemId,
		private int $inputItemMeta,
		private int $ingredientItemId,
		private int $ingredientItemMeta,
		private int $outputItemId,
		private int $outputItemMeta
	){}

	public function getInputItemId() : int{
		return $this->inputItemId;
	}

	public function getInputItemMeta() : int{
		return $this->inputItemMeta;
	}

	public function getIngredientItemId() : int{
		return $this->ingredientItemId;
	}

	public function getIngredientItemMeta() : int{
		return $this->ingredientItemMeta;
	}

	public function getOutputItemId() : int{
		return $this->outputItemId;
	}

	public function getOutputItemMeta() : int{
		return $this->outputItemMeta;
	}
}
