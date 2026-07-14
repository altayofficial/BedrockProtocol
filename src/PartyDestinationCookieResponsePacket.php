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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class PartyDestinationCookieResponsePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PARTY_DESTINATION_COOKIE_RESPONSE_PACKET;

	private string $cookie;
	private bool $accepted;

	/**
	 * @generate-create-func
	 */
	public static function create(string $cookie, bool $accepted) : self{
		$result = new self;
		$result->cookie = $cookie;
		$result->accepted = $accepted;
		return $result;
	}

	public function getCookie() : string{ return $this->cookie; }

	public function isAccepted() : bool{ return $this->accepted; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->cookie = CommonTypes::getString($in);
		$this->accepted = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->cookie);
		CommonTypes::putBool($out, $this->accepted);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePartyDestinationCookieResponse($this);
	}
}
