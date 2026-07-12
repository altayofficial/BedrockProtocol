<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
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
