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

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CreativeGroupEntry{
	public function __construct(
		private int $categoryId,
		private string $categoryName,
		private ItemStack $icon
	){}

	public function getCategoryId() : int{ return $this->categoryId; }

	public function getCategoryName() : string{ return $this->categoryName; }

	public function getIcon() : ItemStack{ return $this->icon; }

	public static function read(ByteBufferReader $in) : self{
		$categoryId = Byte::readUnsigned($in);
		$categoryName = CommonTypes::getString($in);
		$icon = CommonTypes::getItemStackWithoutStackId($in);
		return new self($categoryId, $categoryName, $icon);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->categoryId);
		CommonTypes::putString($out, $this->categoryName);
		CommonTypes::putItemStackWithoutStackId($out, $this->icon);
	}
}
