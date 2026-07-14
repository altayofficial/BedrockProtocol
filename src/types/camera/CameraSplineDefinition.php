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

namespace pocketmine\network\mcpe\protocol\types\camera;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CameraSplineDefinition{

	public function __construct(
		private string $name,
		private CameraSplineInstruction $instruction,
	){}

	public function getName() : string { return $this->name; }

	public function getInstruction() : CameraSplineInstruction { return $this->instruction; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$instruction = CameraSplineInstruction::read($in);
		return new self($name, $instruction);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		$this->instruction->write($out);
	}
}
