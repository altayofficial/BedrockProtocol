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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackresponse;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class ItemStackResponseSlotInfo{
	public function __construct(
		private int $slot,
		private int $hotbarSlot,
		private int $count,
		private ?int $itemStackId,
		private string $customName,
		private string $filteredCustomName,
		private int $durabilityCorrection
	){}

	public function getSlot() : int{ return $this->slot; }

	public function getHotbarSlot() : int{ return $this->hotbarSlot; }

	public function getCount() : int{ return $this->count; }

	public function getItemStackId() : ?int{ return $this->itemStackId; }

	public function getCustomName() : string{ return $this->customName; }

	public function getFilteredCustomName() : string{ return $this->filteredCustomName; }

	public function getDurabilityCorrection() : int{ return $this->durabilityCorrection; }

	public static function read(ByteBufferReader $in) : self{
		$slot = Byte::readUnsigned($in);
		$hotbarSlot = Byte::readUnsigned($in);
		$count = Byte::readUnsigned($in);
		$itemStackId = CommonTypes::readOptional($in, fn(ByteBufferReader $in) => CommonTypes::getBool($in) ? CommonTypes::readServerItemStackId($in) : null);
		$customName = CommonTypes::getString($in);
		$filteredCustomName = CommonTypes::getString($in);
		$durabilityCorrection = VarInt::readSignedInt($in);
		return new self($slot, $hotbarSlot, $count, $itemStackId, $customName, $filteredCustomName, $durabilityCorrection);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->slot);
		Byte::writeUnsigned($out, $this->hotbarSlot);
		Byte::writeUnsigned($out, $this->count);
		CommonTypes::writeOptional($out, $this->itemStackId, function(ByteBufferWriter $out, int $itemStackId) : void{
			CommonTypes::putBool($out, true);
			CommonTypes::writeServerItemStackId($out, $itemStackId);
		});
		CommonTypes::putString($out, $this->customName);
		CommonTypes::putString($out, $this->filteredCustomName);
		VarInt::writeSignedInt($out, $this->durabilityCorrection);
	}
}
