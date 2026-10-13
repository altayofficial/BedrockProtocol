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
use pocketmine\network\mcpe\protocol\types\chat\ChatFormatting;
use pocketmine\network\mcpe\protocol\types\chat\SignedChat;

/**
 * Chat message signed by the Mojang Chat Service
 */
class ChatPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CHAT_PACKET;

	private SignedChat $signedChat;
	private ?ChatFormatting $formatting;

	/**
	 * @generate-create-func
	 */
	public static function create(SignedChat $signedChat, ?ChatFormatting $formatting) : self{
		$result = new self;
		$result->signedChat = $signedChat;
		$result->formatting = $formatting;
		return $result;
	}

	public function getSignedChat() : SignedChat{ return $this->signedChat; }

	public function getFormatting() : ?ChatFormatting{ return $this->formatting; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->signedChat = SignedChat::read($in);
		$this->formatting = CommonTypes::readOptional($in, ChatFormatting::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		$this->signedChat->write($out);
		CommonTypes::writeOptional($out, $this->formatting, fn(ByteBufferWriter $out, ChatFormatting $v) => $v->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleChat($this);
	}
}
