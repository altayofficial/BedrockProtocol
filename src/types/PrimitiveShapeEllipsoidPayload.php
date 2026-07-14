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
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class PrimitiveShapeEllipsoidPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_ELLIPSOID;

	public function __construct(
		private Vector3 $radii,
		private int $segmentsPerAxis,
	){}

	public function getRadii() : Vector3{ return $this->radii; }

	public function getSegmentsPerAxis() : int{ return $this->segmentsPerAxis; }

	public static function read(ByteBufferReader $in) : self{
		return new self(
			CommonTypes::getVector3($in),
			Byte::readUnsigned($in)
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putVector3($out, $this->radii);
		Byte::writeUnsigned($out, $this->segmentsPerAxis);
	}
}
