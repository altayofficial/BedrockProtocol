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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class MapTrackedObject{
	public const TYPE_ENTITY = 0;
	public const TYPE_BLOCK_ENTITY = 1;
	public const TYPE_OTHER = 2;

	public int $type;
	public ?int $actorUniqueId = null;
	public ?BlockPosition $blockPosition = null;

	public static function read(ByteBufferReader $in) : self{
		$result = new self;
		$result->type = LE::readUnsignedInt($in);
		$result->actorUniqueId = CommonTypes::readOptional($in, CommonTypes::getActorUniqueId(...));
		$result->blockPosition = CommonTypes::readOptional($in, CommonTypes::getBlockPosition(...));
		return $result;
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->type);
		CommonTypes::writeOptional($out, $this->actorUniqueId, CommonTypes::putActorUniqueId(...));
		CommonTypes::writeOptional($out, $this->blockPosition, CommonTypes::putBlockPosition(...));
	}
}
