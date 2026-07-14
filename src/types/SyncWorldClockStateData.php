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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see SyncWorldClocksSyncState
 */
final class SyncWorldClockStateData{
	public function __construct(
		private int $clockId,
		private int $time,
		private bool $paused
	){}

	public function getClockId() : int{ return $this->clockId; }

	public function getTime() : int{ return $this->time; }

	public function isPaused() : bool{ return $this->paused; }

	public static function read(ByteBufferReader $in) : self{
		$clockId = VarInt::readUnsignedLong($in);
		$time = VarInt::readSignedInt($in);
		$paused = CommonTypes::getBool($in);

		return new self(
			$clockId,
			$time,
			$paused
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedLong($out, $this->clockId);
		VarInt::writeSignedInt($out, $this->time);
		CommonTypes::putBool($out, $this->paused);
	}
}
