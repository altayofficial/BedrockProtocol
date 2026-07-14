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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class SubChunkPacketEntryCommon{

	public function __construct(
		private SubChunkPositionOffset $offset,
		private int $requestResult,
		private ?string $terrainData,
		private ?SubChunkPacketHeightMapInfo $heightMap,
		private ?SubChunkPacketHeightMapInfo $renderHeightMap
	){}

	public function getOffset() : SubChunkPositionOffset{ return $this->offset; }

	public function getRequestResult() : int{ return $this->requestResult; }

	public function getTerrainData() : ?string{ return $this->terrainData; }

	public function getHeightMap() : ?SubChunkPacketHeightMapInfo{ return $this->heightMap; }

	public function getRenderHeightMap() : ?SubChunkPacketHeightMapInfo{ return $this->renderHeightMap; }

	public static function read(ByteBufferReader $in) : self{
		$offset = SubChunkPositionOffset::read($in);

		$requestResult = Byte::readUnsigned($in);

		$data = CommonTypes::getBool($in) ? CommonTypes::getString($in) : null;

		$heightMapDataType = Byte::readUnsigned($in);
		$heightMap = CommonTypes::getBool($in) ? SubChunkPacketHeightMapInfo::read($in) : null;
		$heightMapData = match ($heightMapDataType) {
			SubChunkPacketHeightMapType::NO_DATA => null,
			SubChunkPacketHeightMapType::DATA => $heightMap ?? throw new PacketDecodeException("Expected heightmap data"),
			SubChunkPacketHeightMapType::ALL_TOO_HIGH => SubChunkPacketHeightMapInfo::allTooHigh(),
			SubChunkPacketHeightMapType::ALL_TOO_LOW => SubChunkPacketHeightMapInfo::allTooLow(),
			default => throw new PacketDecodeException("Unknown heightmap data type $heightMapDataType")
		};

		$renderHeightMapDataType = Byte::readUnsigned($in);
		$renderHeightMap = CommonTypes::getBool($in) ? SubChunkPacketHeightMapInfo::read($in) : null;
		$renderHeightMapData = match ($renderHeightMapDataType) {
			SubChunkPacketHeightMapType::NO_DATA => null,
			SubChunkPacketHeightMapType::DATA => $renderHeightMap ?? throw new PacketDecodeException("Expected render heightmap data"),
			SubChunkPacketHeightMapType::ALL_TOO_HIGH => SubChunkPacketHeightMapInfo::allTooHigh(),
			SubChunkPacketHeightMapType::ALL_TOO_LOW => SubChunkPacketHeightMapInfo::allTooLow(),
			SubChunkPacketHeightMapType::ALL_COPIED => $heightMapData,
			default => throw new PacketDecodeException("Unknown render heightmap data type $renderHeightMapDataType")
		};

		return new self(
			$offset,
			$requestResult,
			$data,
			$heightMapData,
			$renderHeightMapData
		);
	}

	public function write(ByteBufferWriter $out) : void{
		$this->offset->write($out);

		Byte::writeUnsigned($out, $this->requestResult);

		CommonTypes::putBool($out, $this->terrainData !== null);
		if($this->terrainData !== null){
			CommonTypes::putString($out, $this->terrainData);
		}

		if($this->heightMap === null){
			Byte::writeUnsigned($out, SubChunkPacketHeightMapType::NO_DATA);
			CommonTypes::putBool($out, false);
		}elseif($this->heightMap->isAllTooLow()){
			Byte::writeUnsigned($out, SubChunkPacketHeightMapType::ALL_TOO_LOW);
			CommonTypes::putBool($out, false);
		}elseif($this->heightMap->isAllTooHigh()){
			Byte::writeUnsigned($out, SubChunkPacketHeightMapType::ALL_TOO_HIGH);
			CommonTypes::putBool($out, false);
		}else{
			$heightMapData = $this->heightMap; //avoid PHPStan purity issue
			Byte::writeUnsigned($out, SubChunkPacketHeightMapType::DATA);
			CommonTypes::putBool($out, true);
			$heightMapData->write($out);
		}

		if($this->renderHeightMap === null){
			Byte::writeUnsigned($out, SubChunkPacketHeightMapType::ALL_COPIED);
			CommonTypes::putBool($out, false);
		}elseif($this->renderHeightMap->isAllTooLow()){
			Byte::writeUnsigned($out, SubChunkPacketHeightMapType::ALL_TOO_LOW);
			CommonTypes::putBool($out, false);
		}elseif($this->renderHeightMap->isAllTooHigh()){
			Byte::writeUnsigned($out, SubChunkPacketHeightMapType::ALL_TOO_HIGH);
			CommonTypes::putBool($out, false);
		}else{
			$renderHeightMapData = $this->renderHeightMap; //avoid PHPStan purity issue
			Byte::writeUnsigned($out, SubChunkPacketHeightMapType::DATA);
			CommonTypes::putBool($out, true);
			$renderHeightMapData->write($out);
		}
	}
}
