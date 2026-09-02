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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\MatchmakingState;

class ClientboundMatchmakingStatePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_MATCHMAKING_STATE_PACKET;

	private MatchmakingState $state;

	private string $destinationName;

	/**
	 * @generate-create-func
	 */
	public static function create(MatchmakingState $state, string $destinationName) : self{
		$result = new self;
		$result->state = $state;
		$result->destinationName = $destinationName;
		return $result;
	}

	public function getState() : MatchmakingState{ return $this->state; }

	public function getDestinationName() : string{ return $this->destinationName; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->state = MatchmakingState::fromPacket(Byte::readUnsigned($in));
		$this->destinationName = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->state->value);
		CommonTypes::putString($out, $this->destinationName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundMatchmakingState($this);
	}
}
