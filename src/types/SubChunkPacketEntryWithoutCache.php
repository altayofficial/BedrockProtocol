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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class SubChunkPacketEntryWithoutCache{

	public function __construct(
		private SubChunkPacketEntryCommon $base
	){}

	public function getBase() : SubChunkPacketEntryCommon{ return $this->base; }

	public static function read(ByteBufferReader $in) : self{
		$base = SubChunkPacketEntryCommon::read($in);
		if(CommonTypes::getBool($in)){
			LE::readUnsignedLong($in); //blob hash, useless without a cache
		}
		return new self($base);
	}

	public function write(ByteBufferWriter $out) : void{
		$this->base->write($out);
		CommonTypes::putBool($out, false);
	}
}
