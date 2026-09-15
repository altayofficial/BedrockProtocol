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

namespace pocketmine\network\mcpe\protocol\types\furnace;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class FurnaceOptions{

	public function __construct(
		private FurnaceLeftTabIndex $leftFurnaceTab,
		private bool $filtering,
		private FurnaceLayout $furnaceLayout
	){}

	public function getLeftFurnaceTab() : FurnaceLeftTabIndex{ return $this->leftFurnaceTab; }

	public function getFiltering() : bool{ return $this->filtering; }

	public function getFurnaceLayout() : FurnaceLayout{ return $this->furnaceLayout; }

	public static function read(ByteBufferReader $in) : self{
		$leftFurnaceTab = FurnaceLeftTabIndex::fromPacket(VarInt::readSignedInt($in));
		$filtering = CommonTypes::getBool($in);
		$furnaceLayout = FurnaceLayout::fromPacket(VarInt::readSignedInt($in));

		return new self(
			$leftFurnaceTab,
			$filtering,
			$furnaceLayout
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->leftFurnaceTab->value);
		CommonTypes::putBool($out, $this->filtering);
		VarInt::writeSignedInt($out, $this->furnaceLayout->value);
	}
}
