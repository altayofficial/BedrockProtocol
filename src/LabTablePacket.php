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

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\LabTableReactionType;
use pocketmine\network\mcpe\protocol\types\LabTableType;

class LabTablePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::LAB_TABLE_PACKET;

	public LabTableType $type;
	public BlockPosition $position;
	public LabTableReactionType $reaction;

	/**
	 * @generate-create-func
	 */
	public static function create(LabTableType $type, BlockPosition $position, LabTableReactionType $reaction) : self{
		$result = new self;
		$result->type = $type;
		$result->position = $position;
		$result->reaction = $reaction;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->type = LabTableType::fromPacket(Byte::readUnsigned($in));
		$this->position = CommonTypes::getBlockPosition($in);
		$this->reaction = LabTableReactionType::fromPacket(Byte::readUnsigned($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->type->value);
		CommonTypes::putBlockPosition($out, $this->position);
		Byte::writeUnsigned($out, $this->reaction->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLabTable($this);
	}
}
