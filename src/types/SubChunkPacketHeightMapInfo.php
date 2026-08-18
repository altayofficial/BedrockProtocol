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
use function array_fill;
use function count;

class SubChunkPacketHeightMapInfo{

	/**
	 * @param int[] $heights ZZZZXXXX key bit order
	 * @phpstan-param list<int> $heights
	 */
	public function __construct(private array $heights){
		if(count($heights) !== 272){
			throw new \InvalidArgumentException("Expected exactly 272 heightmap values");
		}
	}

	/** @return int[] */
	public function getHeights() : array{ return $this->heights; }

	public function getHeight(int $x, int $z) : int{
		return $this->heights[(($z & 0xf) << 4) | ($x & 0xf)];
	}

	public static function read(ByteBufferReader $in) : self{
		$heights = [];
		for($i = 0; $i < 272; ++$i){
			$heights[] = Byte::readSigned($in);
		}
		return new self($heights);
	}

	public function write(ByteBufferWriter $out) : void{
		for($i = 0; $i < 272; ++$i){
			Byte::writeSigned($out, $this->heights[$i]);
		}
	}

	public static function allTooLow() : self{
		return new self(array_fill(0, 272, -1));
	}

	public static function allTooHigh() : self{
		return new self(array_fill(0, 272, 16));
	}

	public function isAllTooLow() : bool{
		foreach($this->heights as $height){
			if($height >= 0){
				return false;
			}
		}
		return true;
	}

	public function isAllTooHigh() : bool{
		foreach($this->heights as $height){
			if($height <= 15){
				return false;
			}
		}
		return true;
	}
}
