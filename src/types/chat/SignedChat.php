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

namespace pocketmine\network\mcpe\protocol\types\chat;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see ChatPacket
 */
final class SignedChat{

	public function __construct(
		private string $keyId,
		private string $senderId,
		private string $roomId,
		private string $messageId,
		private string $scanText,
		private string $redactedText,
		private string $signature,
	){}

	public function getKeyId() : string{ return $this->keyId; }

	public function getSenderId() : string{ return $this->senderId; }

	public function getRoomId() : string{ return $this->roomId; }

	public function getMessageId() : string{ return $this->messageId; }

	public function getScanText() : string{ return $this->scanText; }

	public function getRedactedText() : string{ return $this->redactedText; }

	public function getSignature() : string{ return $this->signature; }

	public static function read(ByteBufferReader $in) : self{
		$keyId = CommonTypes::getString($in);
		$senderId = CommonTypes::getString($in);
		$roomId = CommonTypes::getString($in);
		$messageId = CommonTypes::getString($in);
		$scanText = CommonTypes::getString($in);
		$redactedText = CommonTypes::getString($in);
		$signature = CommonTypes::getString($in);

		return new self(
			$keyId,
			$senderId,
			$roomId,
			$messageId,
			$scanText,
			$redactedText,
			$signature,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->keyId);
		CommonTypes::putString($out, $this->senderId);
		CommonTypes::putString($out, $this->roomId);
		CommonTypes::putString($out, $this->messageId);
		CommonTypes::putString($out, $this->scanText);
		CommonTypes::putString($out, $this->redactedText);
		CommonTypes::putString($out, $this->signature);
	}
}
