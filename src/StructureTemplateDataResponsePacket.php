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

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

class StructureTemplateDataResponsePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::STRUCTURE_TEMPLATE_DATA_RESPONSE_PACKET;

	public const TYPE_FAILURE = 0;
	public const TYPE_EXPORT = 1;
	public const TYPE_QUERY = 2;

	public string $structureTemplateName;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public ?CacheableNbt $nbt;
	public int $responseType;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $nbt
	 */
	public static function create(string $structureTemplateName, ?CacheableNbt $nbt, int $responseType) : self{
		$result = new self;
		$result->structureTemplateName = $structureTemplateName;
		$result->nbt = $nbt;
		$result->responseType = $responseType;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->structureTemplateName = CommonTypes::getString($in);
		if(CommonTypes::getBool($in)){
			$this->nbt = new CacheableNbt(CommonTypes::getNbtCompoundRoot($in));
		}
		$this->responseType = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->structureTemplateName);
		CommonTypes::putBool($out, $this->nbt !== null);
		if($this->nbt !== null){
			$out->writeByteArray($this->nbt->getEncodedNbt());
		}
		Byte::writeUnsigned($out, $this->responseType);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleStructureTemplateDataResponse($this);
	}
}
