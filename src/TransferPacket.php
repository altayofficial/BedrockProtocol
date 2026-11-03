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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GatheringJoinInfo;
use pocketmine\network\mcpe\protocol\types\TransferPlayerUIOptions;

class TransferPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::TRANSFER_PACKET;

	public string $address;
	public int $port = 19132;
	public bool $reloadWorld;
	public ?GatheringJoinInfo $gatheringJoinInfo = null;
	/** Loading screen customisation for server to server transfers, ignored on world reloads */
	public ?TransferPlayerUIOptions $uiOptions = null;

	/**
	 * @generate-create-func
	 */
	public static function create(string $address, int $port, bool $reloadWorld, ?GatheringJoinInfo $gatheringJoinInfo, ?TransferPlayerUIOptions $uiOptions) : self{
		$result = new self;
		$result->address = $address;
		$result->port = $port;
		$result->reloadWorld = $reloadWorld;
		$result->gatheringJoinInfo = $gatheringJoinInfo;
		$result->uiOptions = $uiOptions;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->address = CommonTypes::getString($in);
		$this->port = LE::readUnsignedShort($in);
		$this->reloadWorld = CommonTypes::getBool($in);
		$this->gatheringJoinInfo = CommonTypes::readOptional($in, GatheringJoinInfo::read(...));
		$this->uiOptions = CommonTypes::readOptional($in, TransferPlayerUIOptions::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->address);
		LE::writeUnsignedShort($out, $this->port);
		CommonTypes::putBool($out, $this->reloadWorld);
		CommonTypes::writeOptional($out, $this->gatheringJoinInfo, fn(ByteBufferWriter $out, GatheringJoinInfo $info) => $info->write($out));
		CommonTypes::writeOptional($out, $this->uiOptions, fn(ByteBufferWriter $out, TransferPlayerUIOptions $options) => $options->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleTransfer($this);
	}
}
