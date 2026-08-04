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

namespace pocketmine\network\mcpe\protocol\types\sound;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;

abstract class SoundData{

	abstract public function getEvent() : SoundDataEvent;

	/**
	 * @throws PacketDecodeException
	 * @throws DataDecodeException
	 */
	public static function read(ByteBufferReader $in) : self{
		$event = SoundDataEvent::fromPacket(VarInt::readUnsignedInt($in));
		return match($event){
			SoundDataEvent::STOP => new StopSoundData(),
			SoundDataEvent::SET_VOLUME => new SetVolumeSoundData(LE::readFloat($in)),
			SoundDataEvent::SET_PITCH => new SetPitchSoundData(LE::readFloat($in)),
			SoundDataEvent::FADE => new FadeSoundData(LE::readFloat($in), LE::readFloat($in)),
			SoundDataEvent::SEEK_TO => new SeekToSoundData(LE::readFloat($in)),
			SoundDataEvent::PAUSE => new PauseSoundData(),
			SoundDataEvent::RESUME => new ResumeSoundData(),
		};
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->getEvent()->value);
		$this->writeData($out);
	}

	protected function writeData(ByteBufferWriter $out) : void{
		//NOOP
	}
}
