<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
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
