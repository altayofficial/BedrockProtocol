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
use pocketmine\network\mcpe\protocol\types\audio\AudioContentPlaybackType;
use pocketmine\network\mcpe\protocol\types\audio\SignedAudioContent;

class ClientboundPlayAudioContentPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_PLAY_AUDIO_CONTENT_PACKET;

	private SignedAudioContent $sharedMetadata;
	private SignedAudioContent $playbackContent;
	private AudioContentPlaybackType $playbackType;
	private PlaySoundPacket $playSound;

	/**
	 * @generate-create-func
	 */
	public static function create(SignedAudioContent $sharedMetadata, SignedAudioContent $playbackContent, AudioContentPlaybackType $playbackType, PlaySoundPacket $playSound) : self{
		$result = new self;
		$result->sharedMetadata = $sharedMetadata;
		$result->playbackContent = $playbackContent;
		$result->playbackType = $playbackType;
		$result->playSound = $playSound;
		return $result;
	}

	public function getSharedMetadata() : SignedAudioContent{ return $this->sharedMetadata; }

	public function getPlaybackContent() : SignedAudioContent{ return $this->playbackContent; }

	public function getPlaybackType() : AudioContentPlaybackType{ return $this->playbackType; }

	public function getPlaySound() : PlaySoundPacket{ return $this->playSound; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->sharedMetadata = SignedAudioContent::read($in);
		$this->playbackContent = SignedAudioContent::read($in);
		$this->playbackType = AudioContentPlaybackType::fromPacket(Byte::readUnsigned($in));

		$this->playSound = new PlaySoundPacket();
		$this->playSound->decodePayload($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		$this->sharedMetadata->write($out);
		$this->playbackContent->write($out);
		Byte::writeUnsigned($out, $this->playbackType->value);
		$this->playSound->encodePayload($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundPlayAudioContent($this);
	}
}
