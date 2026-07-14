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

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;

final class BiomeWeightedData{

	public function __construct(
		private int $biome,
		private int $weight,
	){}

	public function getBiome() : int{ return $this->biome; }

	public function getWeight() : int{ return $this->weight; }

	public static function read(ByteBufferReader $in) : self{
		$biome = LE::readSignedShort($in);
		$weight = LE::readUnsignedInt($in);

		return new self(
			$biome,
			$weight
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeSignedShort($out, $this->biome);
		LE::writeUnsignedInt($out, $this->weight);
	}
}
