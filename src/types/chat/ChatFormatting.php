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

namespace pocketmine\network\mcpe\protocol\types\chat;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see ChatPacket
 */
final class ChatFormatting{

	public function __construct(
		private ?string $namePrefix,
		private ?string $namePostfix,
		private ?string $messagePrefix,
		private ?string $messagePostfix,
	){}

	public function getNamePrefix() : ?string{ return $this->namePrefix; }

	public function getNamePostfix() : ?string{ return $this->namePostfix; }

	public function getMessagePrefix() : ?string{ return $this->messagePrefix; }

	public function getMessagePostfix() : ?string{ return $this->messagePostfix; }

	public static function read(ByteBufferReader $in) : self{
		$namePrefix = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$namePostfix = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$messagePrefix = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$messagePostfix = CommonTypes::readOptional($in, CommonTypes::getString(...));

		return new self(
			$namePrefix,
			$namePostfix,
			$messagePrefix,
			$messagePostfix,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->namePrefix, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->namePostfix, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->messagePrefix, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->messagePostfix, CommonTypes::putString(...));
	}
}
