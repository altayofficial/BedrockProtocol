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

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

class LabTablePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::LAB_TABLE_PACKET;

	public const TYPE_START_COMBINE = 0;
	public const TYPE_START_REACTION = 1;
	public const TYPE_RESET = 2;

	public int $actionType;
	public BlockPosition $blockPosition;
	public int $reactionType;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actionType, BlockPosition $blockPosition, int $reactionType) : self{
		$result = new self;
		$result->actionType = $actionType;
		$result->blockPosition = $blockPosition;
		$result->reactionType = $reactionType;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actionType = Byte::readUnsigned($in);
		$this->blockPosition = CommonTypes::getBlockPosition($in);
		$this->reactionType = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->actionType);
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		Byte::writeUnsigned($out, $this->reactionType);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLabTable($this);
	}
}
