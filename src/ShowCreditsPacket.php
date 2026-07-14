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

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ShowCreditsPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SHOW_CREDITS_PACKET;

	public const STATUS_START_CREDITS = 0;
	public const STATUS_END_CREDITS = 1;

	public int $playerActorRuntimeId;
	public int $status;

	/**
	 * @generate-create-func
	 */
	public static function create(int $playerActorRuntimeId, int $status) : self{
		$result = new self;
		$result->playerActorRuntimeId = $playerActorRuntimeId;
		$result->status = $status;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->playerActorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->status = VarInt::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->playerActorRuntimeId);
		VarInt::writeSignedInt($out, $this->status);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleShowCredits($this);
	}
}
