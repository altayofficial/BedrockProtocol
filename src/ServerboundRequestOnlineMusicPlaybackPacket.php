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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ServerboundRequestOnlineMusicPlaybackPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_REQUEST_ONLINE_MUSIC_PLAYBACK_PACKET;

	private int $requestId;
	private string $soundEventName;

	/**
	 * @generate-create-func
	 */
	public static function create(int $requestId, string $soundEventName) : self{
		$result = new self;
		$result->requestId = $requestId;
		$result->soundEventName = $soundEventName;
		return $result;
	}

	public function getRequestId() : int{ return $this->requestId; }

	public function getSoundEventName() : string{ return $this->soundEventName; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->requestId = LE::readUnsignedInt($in);
		$this->soundEventName = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->requestId);
		CommonTypes::putString($out, $this->soundEventName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerboundRequestOnlineMusicPlayback($this);
	}
}
