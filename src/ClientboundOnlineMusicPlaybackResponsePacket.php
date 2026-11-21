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
use pocketmine\network\mcpe\protocol\types\audio\MusicPlaybackState;
use pocketmine\network\mcpe\protocol\types\audio\SignedAudioContent;

class ClientboundOnlineMusicPlaybackResponsePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_ONLINE_MUSIC_PLAYBACK_RESPONSE_PACKET;

	private int $requestId;
	private MusicPlaybackState $state;
	private SignedAudioContent $sharedMetadata;
	private SignedAudioContent $playbackContent;

	/**
	 * @generate-create-func
	 */
	public static function create(int $requestId, MusicPlaybackState $state, SignedAudioContent $sharedMetadata, SignedAudioContent $playbackContent) : self{
		$result = new self;
		$result->requestId = $requestId;
		$result->state = $state;
		$result->sharedMetadata = $sharedMetadata;
		$result->playbackContent = $playbackContent;
		return $result;
	}

	/**
	 * Echoes the id from the matching ServerboundRequestOnlineMusicPlaybackPacket.
	 */
	public function getRequestId() : int{ return $this->requestId; }

	public function getState() : MusicPlaybackState{ return $this->state; }

	public function getSharedMetadata() : SignedAudioContent{ return $this->sharedMetadata; }

	public function getPlaybackContent() : SignedAudioContent{ return $this->playbackContent; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->requestId = LE::readUnsignedInt($in);
		$this->state = MusicPlaybackState::fromPacket(Byte::readUnsigned($in));
		$this->sharedMetadata = SignedAudioContent::read($in);
		$this->playbackContent = SignedAudioContent::read($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->requestId);
		Byte::writeUnsigned($out, $this->state->value);
		$this->sharedMetadata->write($out);
		$this->playbackContent->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundOnlineMusicPlaybackResponse($this);
	}
}
