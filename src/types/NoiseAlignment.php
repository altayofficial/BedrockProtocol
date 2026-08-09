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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;

final class NoiseAlignment{
	public function __construct(
		private NoiseAlignmentType $type,
		private int $value
	){}

	public function getType() : NoiseAlignmentType{
		return $this->type;
	}

	public function getValue() : int{
		return $this->value;
	}

	public static function read(ByteBufferReader $in) : self{
		$type = NoiseAlignmentType::fromPacket(Byte::readUnsigned($in));
		$value = VarInt::readUnsignedInt($in);
		return new self(
			$type,
			$value
		);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->type->value);
		VarInt::writeUnsignedInt($out, $this->value);
	}
}
