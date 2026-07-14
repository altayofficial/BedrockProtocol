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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ModalFormRequestPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MODAL_FORM_REQUEST_PACKET;

	public int $formId;
	public string $formData; //json

	/**
	 * @generate-create-func
	 */
	public static function create(int $formId, string $formData) : self{
		$result = new self;
		$result->formId = $formId;
		$result->formData = $formData;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->formId = VarInt::readUnsignedInt($in);
		$this->formData = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->formId);
		CommonTypes::putString($out, $this->formData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleModalFormRequest($this);
	}
}
