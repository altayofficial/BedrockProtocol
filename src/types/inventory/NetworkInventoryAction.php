<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class NetworkInventoryAction{
	public const SOURCE_CONTAINER = 0;

	public const SOURCE_GLOBAL = 1;
	public const SOURCE_WORLD = 2; //drop/pickup item entity
	public const SOURCE_CREATIVE = 3;
	public const SOURCE_TODO = 4;

	/**
	 * Fake window IDs for the SOURCE_TODO type (4)
	 *
	 * These identifiers are used for inventory source types which are not currently implemented server-side in MCPE.
	 * As a general rule of thumb, anything that doesn't have a permanent inventory is client-side. These types are
	 * to allow servers to track what is going on in client-side windows.
	 *
	 * Expect these to change in the future.
	 */
	public const SOURCE_TYPE_CRAFTING_RESULT = -4;
	public const SOURCE_TYPE_CRAFTING_USE_INGREDIENT = -5;

	public const SOURCE_TYPE_ANVIL_RESULT = -12;
	public const SOURCE_TYPE_ANVIL_OUTPUT = -13;

	public const SOURCE_TYPE_ENCHANT_OUTPUT = -17;

	public const SOURCE_TYPE_TRADING_INPUT_1 = -20;
	public const SOURCE_TYPE_TRADING_INPUT_2 = -21;
	public const SOURCE_TYPE_TRADING_USE_INPUTS = -22;
	public const SOURCE_TYPE_TRADING_OUTPUT = -23;

	public const SOURCE_TYPE_BEACON = -24;

	public const ACTION_MAGIC_SLOT_CREATIVE_DELETE_ITEM = 0;
	public const ACTION_MAGIC_SLOT_CREATIVE_CREATE_ITEM = 1;

	public const ACTION_MAGIC_SLOT_DROP_ITEM = 0;
	public const ACTION_MAGIC_SLOT_PICKUP_ITEM = 1;

	public int $sourceType;
	public int $windowId = -1;
	public int $sourceFlags = 0;
	public int $inventorySlot;
	public ItemStackWrapper $oldItem;
	public ItemStackWrapper $newItem;

	/**
	 * @return $this
	 *
	 * @throws DataDecodeException
	 * @throws PacketDecodeException
	 */
	public function read(ByteBufferReader $in) : NetworkInventoryAction{
		$this->sourceType = VarInt::readUnsignedInt($in);
		Byte::readUnsigned($in); // has value, always 1
		$variant = VarInt::readUnsignedInt($in);
		if($variant === 1){
			$this->windowId = Byte::readSigned($in);
			$this->sourceFlags = 0;
		} else {
			$this->windowId = -1;
			$this->sourceFlags = VarInt::readUnsignedInt($in);
		}
		Byte::readUnsigned($in); // always 1
		Byte::readUnsigned($in); // always 0

		$this->inventorySlot = VarInt::readUnsignedInt($in);
		$this->oldItem = CommonTypes::getNetworkItemStackDescriptor($in);
		$this->newItem = CommonTypes::getNetworkItemStackDescriptor($in);

		return $this;
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->sourceType);
		Byte::writeUnsigned($out, 1); // has value

		switch($this->sourceType){
			case self::SOURCE_CONTAINER:
			case self::SOURCE_TODO:
				VarInt::writeUnsignedInt($out, 1); // variant = has containerId
				Byte::writeSigned($out, $this->windowId);
				break;
			case self::SOURCE_WORLD:
				VarInt::writeUnsignedInt($out, 0); // variant = has flags
				VarInt::writeUnsignedInt($out, $this->sourceFlags);
				break;
			default:
				VarInt::writeUnsignedInt($out, 0); // variant = no containerId
				VarInt::writeUnsignedInt($out, 0); // no flags
				break;
		}

		Byte::writeUnsigned($out, 1);
		Byte::writeUnsigned($out, 0);

		VarInt::writeUnsignedInt($out, $this->inventorySlot);
		CommonTypes::putNetworkItemStackDescriptor($out, $this->oldItem);
		CommonTypes::putNetworkItemStackDescriptor($out, $this->newItem);
	}
}
