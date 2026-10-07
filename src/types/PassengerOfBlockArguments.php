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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\SetPassengerOfBlockPacket;

/**
 * @see SetPassengerOfBlockPacket
 */
final class PassengerOfBlockArguments{

	public function __construct(
		private BlockPosition $blockPosition,
		private Vector3 $offset,
		private float $rotation,
		private float $rotationLimit,
		private PassengerOfBlockEmoteType $emoteType
	){}

	public function getBlockPosition() : BlockPosition{ return $this->blockPosition; }

	public function getOffset() : Vector3{ return $this->offset; }

	public function getRotation() : float{ return $this->rotation; }

	public function getRotationLimit() : float{ return $this->rotationLimit; }

	public function getEmoteType() : PassengerOfBlockEmoteType{ return $this->emoteType; }

	public static function read(ByteBufferReader $in) : self{
		$blockPosition = CommonTypes::getBlockPosition($in);
		$offset = CommonTypes::getVector3($in);
		$rotation = LE::readFloat($in);
		$rotationLimit = LE::readFloat($in);
		$emoteType = PassengerOfBlockEmoteType::fromPacket(Byte::readUnsigned($in));

		return new self(
			$blockPosition,
			$offset,
			$rotation,
			$rotationLimit,
			$emoteType
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		CommonTypes::putVector3($out, $this->offset);
		LE::writeFloat($out, $this->rotation);
		LE::writeFloat($out, $this->rotationLimit);
		Byte::writeUnsigned($out, $this->emoteType->value);
	}
}
