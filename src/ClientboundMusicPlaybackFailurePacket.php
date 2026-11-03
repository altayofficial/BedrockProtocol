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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\audio\MusicPlaybackFailureEntry;
use function count;

/**
 * Tells the client which music discs couldn't be played, one entry per disc.
 */
class ClientboundMusicPlaybackFailurePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_MUSIC_PLAYBACK_FAILURE_PACKET;

	// hmm, vanilla caps this list at 28 entries
	private const MAX_FAILURES = 28;

	/** @var MusicPlaybackFailureEntry[] */
	private array $failures;

	/**
	 * @generate-create-func
	 * @param MusicPlaybackFailureEntry[] $failures
	 */
	public static function create(array $failures) : self{
		$result = new self;
		$result->failures = $failures;
		return $result;
	}

	/** @return MusicPlaybackFailureEntry[] */
	public function getFailures() : array{ return $this->failures; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$count = VarInt::readUnsignedInt($in);
		if($count > self::MAX_FAILURES){
			throw new PacketDecodeException("Too many music playback failures: $count");
		}
		$this->failures = [];
		for($i = 0; $i < $count; ++$i){
			$this->failures[] = MusicPlaybackFailureEntry::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->failures));
		foreach($this->failures as $failure){
			$failure->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundMusicPlaybackFailure($this);
	}
}
