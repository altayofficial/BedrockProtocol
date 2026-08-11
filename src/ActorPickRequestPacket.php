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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ActorPickRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::ACTOR_PICK_REQUEST_PACKET;

	public int $actorUniqueId;
	public int $maxSlots;
	public bool $withData;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorUniqueId, int $maxSlots, bool $withData) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->maxSlots = $maxSlots;
		$result->withData = $withData;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorUniqueId = LE::readSignedLong($in);
		$this->maxSlots = Byte::readUnsigned($in);
		$this->withData = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeSignedLong($out, $this->actorUniqueId);
		Byte::writeUnsigned($out, $this->maxSlots);
		CommonTypes::putBool($out, $this->withData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleActorPickRequest($this);
	}
}
