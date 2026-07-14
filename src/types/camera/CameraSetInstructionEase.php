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

namespace pocketmine\network\mcpe\protocol\types\camera;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;

final class CameraSetInstructionEase{

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function __construct(
		private int $type,
		private float $duration
	){}

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getType() : int{ return $this->type; }

	public function getDuration() : float{ return $this->duration; }

	public static function read(ByteBufferReader $in) : self{
		$type = Byte::readUnsigned($in);
		$duration = LE::readFloat($in);
		return new self($type, $duration);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->type);
		LE::writeFloat($out, $this->duration);
	}
}
