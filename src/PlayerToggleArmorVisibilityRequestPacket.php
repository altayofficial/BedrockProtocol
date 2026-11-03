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

/**
 * Sent by the client when the player toggles armor visibility. There's no actor id here,
 * the server figures out who sent it from the connection.
 */
class PlayerToggleArmorVisibilityRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_TOGGLE_ARMOR_VISIBILITY_REQUEST_PACKET;

	private bool $hideArmor;

	/**
	 * @generate-create-func
	 */
	public static function create(bool $hideArmor) : self{
		$result = new self;
		$result->hideArmor = $hideArmor;
		return $result;
	}

	public function getHideArmor() : bool{ return $this->hideArmor; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->hideArmor = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->hideArmor);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerToggleArmorVisibilityRequest($this);
	}
}
