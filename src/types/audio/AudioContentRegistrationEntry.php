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

namespace pocketmine\network\mcpe\protocol\types\audio;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\ServerboundRegisterAudioContentPacket;

/**
 * @see ServerboundRegisterAudioContentPacket
 */
final class AudioContentRegistrationEntry{

	public function __construct(
		private string $audioContentId,
		private SignedAudioContent $sharedMetadata,
		private SignedAudioContent $serverContent,
		private SignedAudioContent $playbackContent
	){}

	public function getAudioContentId() : string{ return $this->audioContentId; }

	public function getSharedMetadata() : SignedAudioContent{ return $this->sharedMetadata; }

	public function getServerContent() : SignedAudioContent{ return $this->serverContent; }

	public function getPlaybackContent() : SignedAudioContent{ return $this->playbackContent; }

	public static function read(ByteBufferReader $in) : self{
		$audioContentId = CommonTypes::getString($in);
		$sharedMetadata = SignedAudioContent::read($in);
		$serverContent = SignedAudioContent::read($in);
		$playbackContent = SignedAudioContent::read($in);

		return new self(
			$audioContentId,
			$sharedMetadata,
			$serverContent,
			$playbackContent
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->audioContentId);
		$this->sharedMetadata->write($out);
		$this->serverContent->write($out);
		$this->playbackContent->write($out);
	}
}
