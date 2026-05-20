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

final class FloatRange{

	public function __construct(
		private float $min,
		private float $max
	){}

	public function getMin() : float{ return $this->min; }

	public function getMax() : float{ return $this->max; }

	public static function read(ByteBufferReader $in) : self{
		$min = LE::readFloat($in);
		$max = LE::readFloat($in);
		return new self($min, $max);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->min);
		LE::writeFloat($out, $this->max);
	}
}
