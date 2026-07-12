<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\sound\SoundData;

class ClientboundUpdateSoundDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_UPDATE_SOUND_DATA_PACKET;

	private int $serverSoundHandle;
	private ?SoundData $stop;
	private ?SoundData $setVolume;
	private ?SoundData $setPitch;
	private ?SoundData $fade;
	private ?SoundData $seekTo;
	private ?SoundData $pause;
	private ?SoundData $resume;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $serverSoundHandle,
		?SoundData $stop,
		?SoundData $setVolume,
		?SoundData $setPitch,
		?SoundData $fade,
		?SoundData $seekTo,
		?SoundData $pause,
		?SoundData $resume,
	) : self{
		$result = new self;
		$result->serverSoundHandle = $serverSoundHandle;
		$result->stop = $stop;
		$result->setVolume = $setVolume;
		$result->setPitch = $setPitch;
		$result->fade = $fade;
		$result->seekTo = $seekTo;
		$result->pause = $pause;
		$result->resume = $resume;
		return $result;
	}

	public function getServerSoundHandle() : int{ return $this->serverSoundHandle; }

	public function getStop() : ?SoundData{ return $this->stop; }

	public function getSetVolume() : ?SoundData{ return $this->setVolume; }

	public function getSetPitch() : ?SoundData{ return $this->setPitch; }

	public function getFade() : ?SoundData{ return $this->fade; }

	public function getSeekTo() : ?SoundData{ return $this->seekTo; }

	public function getPause() : ?SoundData{ return $this->pause; }

	public function getResume() : ?SoundData{ return $this->resume; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->serverSoundHandle = LE::readUnsignedLong($in);
		$this->stop = CommonTypes::readOptional($in, SoundData::read(...));
		$this->setVolume = CommonTypes::readOptional($in, SoundData::read(...));
		$this->setPitch = CommonTypes::readOptional($in, SoundData::read(...));
		$this->fade = CommonTypes::readOptional($in, SoundData::read(...));
		$this->seekTo = CommonTypes::readOptional($in, SoundData::read(...));
		$this->pause = CommonTypes::readOptional($in, SoundData::read(...));
		$this->resume = CommonTypes::readOptional($in, SoundData::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedLong($out, $this->serverSoundHandle);
		CommonTypes::writeOptional($out, $this->stop, fn(ByteBufferWriter $out, SoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->setVolume, fn(ByteBufferWriter $out, SoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->setPitch, fn(ByteBufferWriter $out, SoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->fade, fn(ByteBufferWriter $out, SoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->seekTo, fn(ByteBufferWriter $out, SoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->pause, fn(ByteBufferWriter $out, SoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->resume, fn(ByteBufferWriter $out, SoundData $data) => $data->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundUpdateSoundData($this);
	}
}
