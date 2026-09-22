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
use pocketmine\network\mcpe\protocol\types\PassengerOfBlockArguments;

class SetPassengerOfBlockPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_PASSENGER_OF_BLOCK_PACKET;

	private int $passengerActorUniqueId;

	private ?PassengerOfBlockArguments $passengerOfBlockData;

	/**
	 * @generate-create-func
	 */
	public static function create(int $passengerActorUniqueId, ?PassengerOfBlockArguments $passengerOfBlockData) : self{
		$result = new self;
		$result->passengerActorUniqueId = $passengerActorUniqueId;
		$result->passengerOfBlockData = $passengerOfBlockData;
		return $result;
	}

	public function getPassengerActorUniqueId() : int{ return $this->passengerActorUniqueId; }

	public function getPassengerOfBlockData() : ?PassengerOfBlockArguments{ return $this->passengerOfBlockData; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->passengerActorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->passengerOfBlockData = CommonTypes::readOptional($in, PassengerOfBlockArguments::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->passengerActorUniqueId);
		CommonTypes::writeOptional($out, $this->passengerOfBlockData, fn(ByteBufferWriter $out, PassengerOfBlockArguments $data) => $data->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetPassengerOfBlock($this);
	}
}
