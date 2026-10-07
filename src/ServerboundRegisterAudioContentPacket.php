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
use pocketmine\network\mcpe\protocol\types\audio\AudioContentRegistrationEntry;
use function count;

class ServerboundRegisterAudioContentPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_REGISTER_AUDIO_CONTENT_PACKET;

	/**
	 * @var AudioContentRegistrationEntry[]
	 * @phpstan-var list<AudioContentRegistrationEntry>
	 */
	private array $registrations;

	/**
	 * @generate-create-func
	 * @param AudioContentRegistrationEntry[] $registrations
	 * @phpstan-param list<AudioContentRegistrationEntry> $registrations
	 */
	public static function create(array $registrations) : self{
		$result = new self;
		$result->registrations = $registrations;
		return $result;
	}

	/**
	 * @return AudioContentRegistrationEntry[]
	 * @phpstan-return list<AudioContentRegistrationEntry>
	 */
	public function getRegistrations() : array{ return $this->registrations; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->registrations = [];
		$count = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $count; ++$i){
			$this->registrations[] = AudioContentRegistrationEntry::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->registrations));
		foreach($this->registrations as $registration){
			$registration->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerboundRegisterAudioContent($this);
	}
}
