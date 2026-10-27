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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;

abstract class SoundData{

	abstract public function getEvent() : SoundDataEventType;

	/**
	 * @throws PacketDecodeException
	 * @throws DataDecodeException
	 */
	public static function read(ByteBufferReader $in) : self{
		// yeah, the variant index and the type tag inside the variant are always the same value, but both go on the wire
		$variant = VarInt::readUnsignedInt($in);
		$event = SoundDataEventType::fromPacket(Byte::readUnsigned($in));
		if($variant !== $event->value){
			throw new PacketDecodeException("Sound data variant index $variant does not match event type " . $event->value);
		}
		return match($event){
			SoundDataEventType::STOP => new StopSoundData(),
			SoundDataEventType::SET_VOLUME => new SetVolumeSoundData(LE::readFloat($in)),
			SoundDataEventType::SET_PITCH => new SetPitchSoundData(LE::readFloat($in)),
			SoundDataEventType::FADE => new FadeSoundData(LE::readFloat($in), LE::readFloat($in)),
			SoundDataEventType::SEEK_TO => new SeekToSoundData(LE::readFloat($in)),
			SoundDataEventType::PAUSE => new PauseSoundData(),
			SoundDataEventType::RESUME => new ResumeSoundData(),
		};
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->getEvent()->value);
		Byte::writeUnsigned($out, $this->getEvent()->value);
		$this->writeData($out);
	}

	protected function writeData(ByteBufferWriter $out) : void{
		//NOOP
	}
}
