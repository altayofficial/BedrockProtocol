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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class WorldPosition{
	public function __construct(
		private Vector3 $position,
		private int $dimension,
	){}

	public function getPosition() : Vector3{ return $this->position; }

	public function getDimension() : int{ return $this->dimension; }

	public static function read(ByteBufferReader $in) : self{
		$position = CommonTypes::getVector3($in);
		$dimension = VarInt::readSignedInt($in);
		return new self($position, $dimension);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putVector3($out, $this->position);
		VarInt::writeSignedInt($out, $this->dimension);
	}
}
