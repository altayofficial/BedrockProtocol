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
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use function array_fill;
use function count;

class SubChunkPacketHeightMapInfo{

	private const ENTRY_COUNT = 272;
	/** Heights are sent in runs of this many values, each prefixed by its length. */
	private const RUN_LENGTH = 16;

	/**
	 * @param int[] $heights ZZZZXXXX key bit order
	 * @phpstan-param list<int> $heights
	 */
	public function __construct(private array $heights){
		if(count($heights) !== self::ENTRY_COUNT){
			throw new \InvalidArgumentException("Expected exactly " . self::ENTRY_COUNT . " heightmap values");
		}
	}

	/** @return int[] */
	public function getHeights() : array{ return $this->heights; }

	public function getHeight(int $x, int $z) : int{
		return $this->heights[(($z & 0xf) << 4) | ($x & 0xf)];
	}

	public static function read(ByteBufferReader $in) : self{
		$heights = [];
		for($i = 0; $i < self::ENTRY_COUNT; $i += self::RUN_LENGTH){
			$runLength = VarInt::readUnsignedInt($in);
			if($runLength !== self::RUN_LENGTH){
				throw new PacketDecodeException("Expected heightmap run length of " . self::RUN_LENGTH . ", got $runLength");
			}
			for($j = 0; $j < self::RUN_LENGTH; ++$j){
				$heights[] = Byte::readSigned($in);
			}
		}
		return new self($heights);
	}

	public function write(ByteBufferWriter $out) : void{
		for($i = 0; $i < self::ENTRY_COUNT; $i += self::RUN_LENGTH){
			VarInt::writeUnsignedInt($out, self::RUN_LENGTH);
			for($j = 0; $j < self::RUN_LENGTH; ++$j){
				Byte::writeSigned($out, $this->heights[$i + $j]);
			}
		}
	}

	public static function allTooLow() : self{
		return new self(array_fill(0, self::ENTRY_COUNT, -1));
	}

	public static function allTooHigh() : self{
		return new self(array_fill(0, self::ENTRY_COUNT, 16));
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
