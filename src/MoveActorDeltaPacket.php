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
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class MoveActorDeltaPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOVE_ACTOR_DELTA_PACKET;

	public int $actorRuntimeId;
	public ?float $xPos = null;
	public ?float $yPos = null;
	public ?float $zPos = null;
	public ?float $pitch = null;
	public ?float $yaw = null;
	public ?float $headYaw = null;
	public bool $onGround = false;
	public bool $teleport = false;
	public bool $forceMoveLocalEntity = false;
	public bool $forceCompletion = false;
	public int $ticks = 0;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $actorRuntimeId,
		?float $xPos,
		?float $yPos,
		?float $zPos,
		?float $pitch,
		?float $yaw,
		?float $headYaw,
		bool $onGround,
		bool $teleport,
		bool $forceMoveLocalEntity,
		bool $forceCompletion,
		int $ticks
	) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->xPos = $xPos;
		$result->yPos = $yPos;
		$result->zPos = $zPos;
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->headYaw = $headYaw;
		$result->onGround = $onGround;
		$result->teleport = $teleport;
		$result->forceMoveLocalEntity = $forceMoveLocalEntity;
		$result->forceCompletion = $forceCompletion;
		$result->ticks = $ticks;
		return $result;
	}

	/** @throws DataDecodeException */
	private static function maybeReadCoord(ByteBufferReader $in) : ?float{
		if(CommonTypes::getBool($in)){
			return LE::readFloat($in);
		}
		return null;
	}

	/** @throws DataDecodeException */
	private static function maybeReadRotation(ByteBufferReader $in) : ?float{
		if(CommonTypes::getBool($in)){
			return CommonTypes::getRotationByte($in);
		}
		return null;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->xPos = self::maybeReadCoord($in);
		$this->yPos = self::maybeReadCoord($in);
		$this->zPos = self::maybeReadCoord($in);
		$this->pitch = self::maybeReadRotation($in);
		$this->yaw = self::maybeReadRotation($in);
		$this->headYaw = self::maybeReadRotation($in);
		$this->onGround = CommonTypes::getBool($in);
		$this->teleport = CommonTypes::getBool($in);
		$this->forceMoveLocalEntity = CommonTypes::getBool($in);
		$this->forceCompletion = CommonTypes::getBool($in);
		$this->ticks = VarInt::readUnsignedLong($in);
	}

	private static function maybeWriteCoord(ByteBufferWriter $out, ?float $val) : void{
		CommonTypes::putBool($out, $val !== null);
		if($val !== null){
			LE::writeFloat($out, $val);
		}
	}

	private static function maybeWriteRotation(ByteBufferWriter $out, ?float $val) : void{
		CommonTypes::putBool($out, $val !== null);
		if($val !== null){
			CommonTypes::putRotationByte($out, $val);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		self::maybeWriteCoord($out, $this->xPos);
		self::maybeWriteCoord($out, $this->yPos);
		self::maybeWriteCoord($out, $this->zPos);
		self::maybeWriteRotation($out, $this->pitch);
		self::maybeWriteRotation($out, $this->yaw);
		self::maybeWriteRotation($out, $this->headYaw);
		CommonTypes::putBool($out, $this->onGround);
		CommonTypes::putBool($out, $this->teleport);
		CommonTypes::putBool($out, $this->forceMoveLocalEntity);
		CommonTypes::putBool($out, $this->forceCompletion);
		VarInt::writeUnsignedLong($out, $this->ticks);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMoveActorDelta($this);
	}
}
