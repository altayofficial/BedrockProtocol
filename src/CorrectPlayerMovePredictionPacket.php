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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\RewindType;

class CorrectPlayerMovePredictionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CORRECT_PLAYER_MOVE_PREDICTION_PACKET;

	private RewindType $predictionType;
	private Vector3 $position;
	private Vector3 $delta; // posDelta on the protocol docs, their naming is sucks
	private Vector2 $rotation;
	private ?float $vehicleAngularVelocity;
	private bool $onGround;
	private int $tick;

	/**
	 * @generate-create-func
	 */
	public static function create(
		RewindType $predictionType,
		Vector3 $position,
		Vector3 $delta,
		Vector2 $rotation,
		?float $vehicleAngularVelocity,
		bool $onGround,
		int $tick,
	) : self{
		$result = new self;
		$result->predictionType = $predictionType;
		$result->position = $position;
		$result->delta = $delta;
		$result->rotation = $rotation;
		$result->vehicleAngularVelocity = $vehicleAngularVelocity;
		$result->onGround = $onGround;
		$result->tick = $tick;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->predictionType = RewindType::fromPacket(Byte::readUnsigned($in));
		$this->position = CommonTypes::getVector3($in);
		$this->delta = CommonTypes::getVector3($in);
		$this->rotation = CommonTypes::getVector2($in);
		$this->vehicleAngularVelocity = CommonTypes::readOptional($in, LE::readFloat(...));
		$this->onGround = CommonTypes::getBool($in);
		$this->tick = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->predictionType->value);
		CommonTypes::putVector3($out, $this->position);
		CommonTypes::putVector3($out, $this->delta);
		CommonTypes::putVector2($out, $this->rotation);
		CommonTypes::writeOptional($out, $this->vehicleAngularVelocity, LE::writeFloat(...));
		CommonTypes::putBool($out, $this->onGround);
		VarInt::writeUnsignedLong($out, $this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCorrectPlayerMovePrediction($this);
	}
}
