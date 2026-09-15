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
use pocketmine\color\Color;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class PrimitiveShapeTextPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_TEXT;

	public function __construct(
		private string $text,
		private bool $useRotation,
		private ?Color $backgroundColor,
		private float $lineGapHeight,
		private bool $depthTest,
		private bool $showBackface,
		private bool $showTextBackface,
	){}

	public function getText() : string{ return $this->text; }

	public function getUseRotation() : bool{ return $this->useRotation; }

	public function getBackgroundColor() : ?Color{ return $this->backgroundColor; }

	public function getLineGapHeight() : float{ return $this->lineGapHeight; }

	public function getDepthTest() : bool{ return $this->depthTest; }

	public function getShowBackface() : bool{ return $this->showBackface; }

	public function getShowTextBackface() : bool{ return $this->showTextBackface; }

	public static function read(ByteBufferReader $in) : self{
		$text = CommonTypes::getString($in);
		$useRotation = CommonTypes::getBool($in);
		$backgroundColor = CommonTypes::readOptional($in, fn() => Color::fromARGB(LE::readUnsignedInt($in)));
		$lineGapHeight = LE::readFloat($in);
		$depthTest = CommonTypes::getBool($in);
		$showBackface = CommonTypes::getBool($in);
		$showTextBackface = CommonTypes::getBool($in);
		return new self(
			$text,
			$useRotation,
			$backgroundColor,
			$lineGapHeight,
			$depthTest,
			$showBackface,
			$showTextBackface
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->text);
		CommonTypes::putBool($out, $this->useRotation);
		CommonTypes::writeOptional($out, $this->backgroundColor, fn(ByteBufferWriter $out, Color $color) => LE::writeUnsignedInt($out, $color->toARGB()));
		LE::writeFloat($out, $this->lineGapHeight);
		CommonTypes::putBool($out, $this->depthTest);
		CommonTypes::putBool($out, $this->showBackface);
		CommonTypes::putBool($out, $this->showTextBackface);
	}
}
