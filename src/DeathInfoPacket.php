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
use pocketmine\network\mcpe\protocol\types\DeathCauseMessageType;

/**
 * Sets the message shown on the death screen underneath "You died!"
 */
class DeathInfoPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::DEATH_INFO_PACKET;

	public DeathCauseMessageType $deathCauseMessageType;

	/**
	 * @generate-create-func
	 */
	public static function create(DeathCauseMessageType $deathCauseMessageType) : self{
		$result = new self;
		$result->deathCauseMessageType = $deathCauseMessageType;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		DeathCauseMessageType::read($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		$this->deathCauseMessageType->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleDeathInfo($this);
	}
}
