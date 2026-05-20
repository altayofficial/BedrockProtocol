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

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class NoiseDescriptor{

	/**
	 * @param float[] $amplitudes
	 */
	public function __construct(
		private string $name,
		private int $firstOctave,
		private array $amplitudes
	){}

	public function getName() : string{ return $this->name; }

	public function getFirstOctave() : int{ return $this->firstOctave; }

	/**
	 * @return float[]
	 */
	public function getAmplitudes() : array{ return $this->amplitudes; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$firstOctave = LE::readSignedInt($in);

		$amplitudes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$amplitudes[] = LE::readFloat($in);
		}

		return new self($name, $firstOctave, $amplitudes);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		LE::writeSignedInt($out, $this->firstOctave);

		VarInt::writeUnsignedInt($out, count($this->amplitudes));
		foreach($this->amplitudes as $amplitude){
			LE::writeFloat($out, $amplitude);
		}
	}
}
