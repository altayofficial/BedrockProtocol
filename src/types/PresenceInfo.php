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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see ServerPresenceInfoPacket&ServerJoinInformation
 */
final class PresenceInfo{
	public function __construct(
		private ?string $richPresenceId
	){}

	public function getRichPresenceId() : ?string{ return $this->richPresenceId; }

	public static function read(ByteBufferReader $in) : self{
		$richPresenceId = CommonTypes::readOptional($in, CommonTypes::getString(...));

		return new self($richPresenceId);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->richPresenceId, CommonTypes::putString(...));
	}
}
