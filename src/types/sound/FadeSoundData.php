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

final class FadeSoundData extends SoundData{

	public function __construct(
		private float $duration,
		private float $targetVolume
	){}

	public function getDuration() : float{ return $this->duration; }

	public function getTargetVolume() : float{ return $this->targetVolume; }

	public function getEvent() : SoundDataEvent{ return SoundDataEvent::FADE; }

	protected function writeData(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->duration);
		LE::writeFloat($out, $this->targetVolume);
	}
}
