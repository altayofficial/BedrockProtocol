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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

/**
 * @see SyncWorldClocksInitializeRegistry
 */
final class SyncWorldClockData{

	/**
	 * @param SyncWorldClockMarkerData[] $markers
	 * @phpstan-param list<SyncWorldClockMarkerData> $markers
	 */
	public function __construct(
		private int $id,
		private string $name,
		private int $time,
		private bool $paused,
		private array $markers
	){}

	public function getId() : int{ return $this->id; }

	public function getName() : string{ return $this->name; }

	public function getTime() : int{ return $this->time; }

	public function isPaused() : bool{ return $this->paused; }

	/**
	 * @return SyncWorldClockMarkerData[]
	 * @phpstan-return list<SyncWorldClockMarkerData>
	 */
	public function getMarkers() : array{ return $this->markers; }

	public static function read(ByteBufferReader $in) : self{
		$id = VarInt::readUnsignedLong($in);
		$name = CommonTypes::getString($in);
		$time = VarInt::readSignedInt($in);
		$paused = CommonTypes::getBool($in);

		$markers = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$markers[] = SyncWorldClockMarkerData::read($in);
		}

		return new self(
			$id,
			$name,
			$time,
			$paused,
			$markers
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedLong($out, $this->id);
		CommonTypes::putString($out, $this->name);
		VarInt::writeSignedInt($out, $this->time);
		CommonTypes::putBool($out, $this->paused);

		VarInt::writeUnsignedInt($out, count($this->markers));
		foreach($this->markers as $marker){
			$marker->write($out);
		}
	}
}
