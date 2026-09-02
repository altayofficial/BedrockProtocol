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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ClientboundStonecutterSetRecipePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_STONECUTTER_SET_RECIPE_PACKET;

	private int $actorUniqueId;
	private int $containerId;
	private int $recipeIndex;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorUniqueId, int $containerId, int $recipeIndex) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->containerId = $containerId;
		$result->recipeIndex = $recipeIndex;
		return $result;
	}

	public function getActorUniqueId() : int{ return $this->actorUniqueId; }

	public function getContainerId() : int{ return $this->containerId; }

	public function getRecipeIndex() : int{ return $this->recipeIndex; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->containerId = Byte::readUnsigned($in);
		$this->recipeIndex = VarInt::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->actorUniqueId);
		Byte::writeUnsigned($out, $this->containerId);
		VarInt::writeSignedInt($out, $this->recipeIndex);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundStonecutterSetRecipe($this);
	}
}
