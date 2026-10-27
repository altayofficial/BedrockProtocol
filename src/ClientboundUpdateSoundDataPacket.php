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
use pocketmine\network\mcpe\protocol\types\sound\SoundData;

class ClientboundUpdateSoundDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_UPDATE_SOUND_DATA_PACKET;

	private int $serverSoundHandle;
	private SoundData $event;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $serverSoundHandle,
		SoundData $event,
	) : self{
		$result = new self;
		$result->serverSoundHandle = $serverSoundHandle;
		$result->event = $event;
		return $result;
	}

	public function getServerSoundHandle() : int{ return $this->serverSoundHandle; }

	public function getEvent() : SoundData{ return $this->event; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->serverSoundHandle = LE::readUnsignedLong($in);
		$this->event = SoundData::read($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedLong($out, $this->serverSoundHandle);
		$this->event->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundUpdateSoundData($this);
	}
}
