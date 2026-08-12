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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\LegacyTelemetryEventType;

class LegacyTelemetryEventPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LEGACY_TELEMETRY_EVENT_PACKET;

	public int $targetActorId;
	public LegacyTelemetryEventType $type;
	public bool $usePlayerId; // wtf
	public int $eventData;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $targetActorId,
		LegacyTelemetryEventType $type,
		bool $usePlayerId,
		int $eventData,
	) : self{
		$result = new self;
		$result->targetActorId = $targetActorId;
		$result->type = $type;
		$result->usePlayerId = $usePlayerId;
		$result->eventData = $eventData;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->targetActorId = CommonTypes::getActorUniqueId($in);
		$this->type = LegacyTelemetryEventType::fromPacket(Byte::readUnsigned($in));
		$this->usePlayerId = CommonTypes::getBool($in);
		$this->eventData = VarInt::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->targetActorId);
		Byte::writeUnsigned($out, $this->type->value);
		CommonTypes::putBool($out, $this->usePlayerId);
		VarInt::writeSignedInt($out, $this->eventData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLegacyTelemetryEvent($this);
	}
}
