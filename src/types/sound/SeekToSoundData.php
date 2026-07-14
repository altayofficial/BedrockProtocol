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

namespace pocketmine\network\mcpe\protocol\types\sound;

use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;

final class SeekToSoundData extends SoundData{

	public function __construct(
		private float $seconds
	){}

	public function getSeconds() : float{ return $this->seconds; }

	public function getEvent() : SoundDataEvent{ return SoundDataEvent::SEEK_TO; }

	protected function writeData(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->seconds);
	}
}
