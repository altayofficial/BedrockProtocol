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
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use function count;
use function strlen;

/**
 * Opens a container screen onto a virtual container, one that only lives in memory with no block or entity behind it.
 * The slot count is the container size, so no InventoryContentPacket follows, and the client never acks a successful open.
 */
class VirtualContainerOpenPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::VIRTUAL_CONTAINER_OPEN_PACKET;

	private const MAX_TITLE_LENGTH = 256;
	private const MIN_SLOTS = 1;
	private const MAX_SLOTS = 54;

	private int $windowId;
	private int $sessionId;
	private string $title;
	/** @var ItemStackWrapper[] */
	private array $slots;

	/**
	 * @generate-create-func
	 * @param ItemStackWrapper[] $slots
	 */
	public static function create(int $windowId, int $sessionId, string $title, array $slots) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->sessionId = $sessionId;
		$result->title = $title;
		$result->slots = $slots;
		return $result;
	}

	public function getWindowId() : int{ return $this->windowId; }

	/** Nonzero id every VirtualContainerClosePacket for this screen refers back to */
	public function getSessionId() : int{ return $this->sessionId; }

	/** Localized on the client when it's a localization key */
	public function getTitle() : string{ return $this->title; }

	/** @return ItemStackWrapper[] */
	public function getSlots() : array{ return $this->slots; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->windowId = Byte::readUnsigned($in);
		$this->sessionId = VarInt::readUnsignedLong($in);
		if($this->sessionId === 0){
			throw new PacketDecodeException("Virtual container session ID must be nonzero");
		}
		$this->title = CommonTypes::getString($in);
		if(strlen($this->title) > self::MAX_TITLE_LENGTH){
			throw new PacketDecodeException("Virtual container title too long: " . strlen($this->title) . " bytes");
		}
		$count = VarInt::readUnsignedInt($in);
		if($count < self::MIN_SLOTS || $count > self::MAX_SLOTS){
			throw new PacketDecodeException("Invalid virtual container slot count: $count");
		}
		$this->slots = [];
		for($i = 0; $i < $count; ++$i){
			$this->slots[] = CommonTypes::getNetworkItemStackDescriptor($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->windowId);
		VarInt::writeUnsignedLong($out, $this->sessionId);
		CommonTypes::putString($out, $this->title);
		VarInt::writeUnsignedInt($out, count($this->slots));
		foreach($this->slots as $slot){
			CommonTypes::putNetworkItemStackDescriptor($out, $slot);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleVirtualContainerOpen($this);
	}
}
