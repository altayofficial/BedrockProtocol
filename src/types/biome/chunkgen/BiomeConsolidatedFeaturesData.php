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
use pmmp\encoding\VarInt;
use function count;

final class BiomeConsolidatedFeaturesData{

	/**
	 * @param BiomeConsolidatedFeatureData[] $features
	 */
	public function __construct(
		private array $features,
	){}

	/**
	 * @return BiomeConsolidatedFeatureData[]
	 */
	public function getFeatures() : array{ return $this->features; }

	public static function read(ByteBufferReader $in) : self{
		$features = [];

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$features[] = BiomeConsolidatedFeatureData::read($in);
		}

		return new self($features);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->features));
		foreach($this->features as $feature){
			$feature->write($out);
		}
	}
}
