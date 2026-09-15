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
use Ramsey\Uuid\UuidInterface;

final class DimensionData{

	public function __construct(
		private int $minimumY,
		private int $heightRange,
		private int $generator,
		private int $dimensionType,
		private UuidInterface $packId,
		private string $defaultBiome
	){}

	public function getMinimumY() : int{ return $this->minimumY; }

	public function getHeightRange() : int{ return $this->heightRange; }

	public function getGenerator() : int{ return $this->generator; }

	public function getDimensionType() : int{ return $this->dimensionType; }

	public function getPackId() : UuidInterface{ return $this->packId; }

	public function getDefaultBiome() : string{ return $this->defaultBiome; }

	public static function read(ByteBufferReader $in) : self{
		$minimumY = VarInt::readSignedInt($in);
		$heightRange = VarInt::readSignedInt($in);
		$generator = VarInt::readSignedInt($in);
		$dimensionType = VarInt::readSignedInt($in);
		$packId = CommonTypes::getUUID($in);
		$defaultBiome = CommonTypes::getString($in); // max length 256, not sure client disconnects or not so didn't added a check

		return new self($minimumY, $heightRange, $generator, $dimensionType, $packId, $defaultBiome);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->minimumY);
		VarInt::writeSignedInt($out, $this->heightRange);
		VarInt::writeSignedInt($out, $this->generator);
		VarInt::writeSignedInt($out, $this->dimensionType);
		CommonTypes::putUUID($out, $this->packId);
		CommonTypes::putString($out, $this->defaultBiome);
	}
}
