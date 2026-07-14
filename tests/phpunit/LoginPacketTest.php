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

use PHPUnit\Framework\TestCase;
use pmmp\encoding\BE;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function strlen;

class LoginPacketTest extends TestCase{

	public function testInvalidChainDataJsonHandling() : void{
		$stream = new ByteBufferWriter();
		VarInt::writeUnsignedInt($stream, ProtocolInfo::LOGIN_PACKET);
		BE::writeUnsignedInt($stream, ProtocolInfo::CURRENT_PROTOCOL);

		$payload = '{"chain":[]'; //intentionally malformed
		$stream2 = new ByteBufferWriter();
		LE::writeUnsignedInt($stream2, strlen($payload));
		$stream2->writeByteArray($payload);

		CommonTypes::putString($stream, $stream2->getData());

		$pk = PacketPool::getInstance()->getPacket($stream->getData());
		self::assertInstanceOf(LoginPacket::class, $pk);

		$this->expectException(PacketDecodeException::class);
		$pk->decode(new ByteBufferReader($stream->getData())); //bang
	}
}
