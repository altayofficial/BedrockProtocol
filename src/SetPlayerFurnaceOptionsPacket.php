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
use pocketmine\network\mcpe\protocol\types\furnace\FurnaceOptions;
use pocketmine\network\mcpe\protocol\types\furnace\FurnaceType;

class SetPlayerFurnaceOptionsPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::SET_PLAYER_FURNACE_OPTIONS_PACKET;

	public FurnaceType $furnaceType;
	public FurnaceOptions $furnaceOptions;

	/**
	 * @generate-create-func
	 */
	public static function create(FurnaceType $furnaceType, FurnaceOptions $furnaceOptions) : self{
		$result = new self;
		$result->furnaceType = $furnaceType;
		$result->furnaceOptions = $furnaceOptions;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->furnaceType = FurnaceType::fromPacket(Byte::readUnsigned($in));
		$this->furnaceOptions = FurnaceOptions::read($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->furnaceType->value);
		$this->furnaceOptions->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetPlayerFurnaceOptions($this);
	}
}
