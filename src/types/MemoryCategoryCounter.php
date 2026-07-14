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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;

final class MemoryCategoryCounter{

	public function __construct(
		private int $category,
		private int $bytes
	){}

	public function getCategory() : int{ return $this->category; }

	public function getBytes() : int{ return $this->bytes; }

	public static function read(ByteBufferReader $in) : self{
		$category = Byte::readUnsigned($in);
		$bytes = LE::readUnsignedLong($in);

		return new self(
			$category,
			$bytes
		);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->category);
		LE::writeUnsignedLong($out, $this->bytes);
	}
}
