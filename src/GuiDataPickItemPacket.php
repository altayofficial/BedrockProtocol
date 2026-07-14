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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class GuiDataPickItemPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::GUI_DATA_PICK_ITEM_PACKET;

	public string $itemDescription;
	public string $itemEffects;
	public int $hotbarSlot;

	/**
	 * @generate-create-func
	 */
	public static function create(string $itemDescription, string $itemEffects, int $hotbarSlot) : self{
		$result = new self;
		$result->itemDescription = $itemDescription;
		$result->itemEffects = $itemEffects;
		$result->hotbarSlot = $hotbarSlot;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->itemDescription = CommonTypes::getString($in);
		$this->itemEffects = CommonTypes::getString($in);
		$this->hotbarSlot = LE::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->itemDescription);
		CommonTypes::putString($out, $this->itemEffects);
		LE::writeSignedInt($out, $this->hotbarSlot);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleGuiDataPickItem($this);
	}
}
