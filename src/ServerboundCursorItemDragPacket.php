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
use pocketmine\network\mcpe\protocol\types\CursorItemDragState;

/**
 * Sent when the client starts or stops a cursor item split drag.
 */
class ServerboundCursorItemDragPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_CURSOR_ITEM_DRAG_PACKET;

	private CursorItemDragState $state;

	/**
	 * @generate-create-func
	 */
	public static function create(CursorItemDragState $state) : self{
		$result = new self;
		$result->state = $state;
		return $result;
	}

	public function getState() : CursorItemDragState{ return $this->state; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->state = CursorItemDragState::fromPacket(Byte::readUnsigned($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->state->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerboundCursorItemDrag($this);
	}
}
