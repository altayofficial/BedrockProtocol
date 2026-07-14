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

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class FullContainerName{
	public function __construct(
		private int $containerId,
		private ?int $dynamicId = null
	){}

	public function getContainerId() : int{ return $this->containerId; }

	public function getDynamicId() : ?int{ return $this->dynamicId; }

	public static function read(ByteBufferReader $in) : self{
		$containerId = Byte::readUnsigned($in);
		$dynamicId = CommonTypes::readOptional($in, LE::readUnsignedInt(...));
		return new self($containerId, $dynamicId);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->containerId);
		CommonTypes::writeOptional($out, $this->dynamicId, LE::writeUnsignedInt(...));
	}
}
