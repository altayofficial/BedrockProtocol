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
use pmmp\encoding\LE;
use pocketmine\math\Vector2;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class PrimitiveShapeConePayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_CONE;

	public function __construct(
		private Vector2 $radii,
		private float $height,
		private int $segments,
	){}

	public function getRadii() : Vector2{ return $this->radii; }

	public function getHeight() : float{ return $this->height; }

	public function getSegments() : int{ return $this->segments; }

	public static function read(ByteBufferReader $in) : self{
		return new self(
			CommonTypes::getVector2($in),
			LE::readFloat($in),
			Byte::readUnsigned($in)
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putVector2($out, $this->radii);
		LE::writeFloat($out, $this->height);
		Byte::writeUnsigned($out, $this->segments);
	}
}
