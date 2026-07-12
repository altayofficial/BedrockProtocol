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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * This is used for PlayerAuthInput packet when the flags include PERFORM_BLOCK_ACTIONS
 */
final class PlayerBlockActionWithBlockInfo implements PlayerBlockAction{
	public function __construct(
		private int $actionType,
		private BlockPosition $blockPosition,
		private int $face
	){}

	public function getActionType() : int{ return $this->actionType; }

	public function getBlockPosition() : BlockPosition{ return $this->blockPosition; }

	public function getFace() : int{ return $this->face; }

	public static function read(ByteBufferReader $in, int $actionType) : self{
		$blockPosition = CommonTypes::getBlockPosition($in);
		$face = VarInt::readSignedInt($in);
		return new self($actionType, $blockPosition, $face);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		VarInt::writeSignedInt($out, $this->face);
	}
}
