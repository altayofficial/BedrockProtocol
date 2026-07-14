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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class PrimitiveShapePyramidPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_PYRAMID;

	public function __construct(
		private float $width,
		private ?float $depth,
		private float $height,
	){}

	public function getWidth() : float{ return $this->width; }

	public function getDepth() : ?float{ return $this->depth; }

	public function getHeight() : float{ return $this->height; }

	public static function read(ByteBufferReader $in) : self{
		return new self(
			LE::readFloat($in),
			CommonTypes::readOptional($in, LE::readFloat(...)),
			LE::readFloat($in)
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->width);
		CommonTypes::writeOptional($out, $this->depth, LE::writeFloat(...));
		LE::writeFloat($out, $this->height);
	}
}
