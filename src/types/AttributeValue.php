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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;

/**
 * @see AttributeEnvironment
 */
abstract class AttributeValue{

	abstract public function getTypeId() : int;

	abstract public function write(ByteBufferWriter $out) : void;

	public static function read(ByteBufferReader $in) : self{
		return match(VarInt::readUnsignedInt($in)){
			AttributeValueBool::ID => AttributeValueBool::read($in),
			AttributeValueFloat::ID => AttributeValueFloat::read($in),
			AttributeValueColor::ID => AttributeValueColor::read($in),
			default => throw new PacketDecodeException("Unknown AttributeValue type"),
		};
	}
}
