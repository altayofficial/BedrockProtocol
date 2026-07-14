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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\color\Color;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\network\mcpe\protocol\types\MapDecoration;
use pocketmine\network\mcpe\protocol\types\MapTrackedObject;
use pocketmine\utils\Binary;
use function count;

class ClientboundMapItemDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_MAP_ITEM_DATA_PACKET;

	public int $mapId;
	public int $dimensionId = DimensionIds::OVERWORLD;
	public bool $isLocked = false;
	public BlockPosition $origin;

	/**
	 * @var int[]|null
	 * @phpstan-var list<int>|null
	 */
	public ?array $creationMapIds = null;
	public ?int $scale = null;

	/**
	 * @var MapTrackedObject[]|null
	 * @phpstan-var list<MapTrackedObject>|null
	 */
	public ?array $trackedEntities = null;
	/**
	 * @var MapDecoration[]|null
	 * @phpstan-var list<MapDecoration>|null
	 */
	public ?array $decorations = null;

	public ?int $width = null;
	public ?int $height = null;
	public ?int $startX = null;
	public ?int $startY = null;

	/**
	 * @var Color[]|null
	 * @phpstan-var list<Color>|null
	 */
	public ?array $pixels = null;

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->mapId = CommonTypes::getActorUniqueId($in);
		$this->dimensionId = Byte::readUnsigned($in);
		$this->isLocked = CommonTypes::getBool($in);
		$this->origin = CommonTypes::getBlockPosition($in);

		$this->creationMapIds = CommonTypes::readOptional($in, function(ByteBufferReader $in) : array{
			$creationMapIds = [];
			for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
				$creationMapIds[] = CommonTypes::getActorUniqueId($in);
			}
			return $creationMapIds;
		});

		$this->scale = CommonTypes::readOptional($in, Byte::readUnsigned(...));

		$this->trackedEntities = CommonTypes::readOptional($in, function(ByteBufferReader $in) : array{
			$trackedEntities = [];
			for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
				$trackedEntities[] = MapTrackedObject::read($in);
			}
			return $trackedEntities;
		});

		$this->decorations = CommonTypes::readOptional($in, function(ByteBufferReader $in) : array{
			$decorations = [];
			for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
				$icon = Byte::readUnsigned($in);
				$rotation = Byte::readUnsigned($in);
				$xOffset = Byte::readUnsigned($in);
				$yOffset = Byte::readUnsigned($in);
				$label = CommonTypes::getString($in);
				$color = Color::fromRGBA(Binary::flipIntEndianness(LE::readUnsignedInt($in)));
				$decorations[] = new MapDecoration($icon, $rotation, $xOffset, $yOffset, $label, $color);
			}
			return $decorations;
		});

		$this->width = CommonTypes::readOptional($in, VarInt::readSignedInt(...));
		$this->height = CommonTypes::readOptional($in, VarInt::readSignedInt(...));
		$this->startX = CommonTypes::readOptional($in, VarInt::readSignedInt(...));
		$this->startY = CommonTypes::readOptional($in, VarInt::readSignedInt(...));

		$this->pixels = CommonTypes::readOptional($in, function(ByteBufferReader $in) : array{
			$pixels = [];
			for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
				$pixels[] = Color::fromRGBA(Binary::flipIntEndianness(LE::readUnsignedInt($in)));
			}
			return $pixels;
		});
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->mapId);
		Byte::writeUnsigned($out, $this->dimensionId);
		CommonTypes::putBool($out, $this->isLocked);
		CommonTypes::putBlockPosition($out, $this->origin);

		CommonTypes::writeOptional($out, $this->creationMapIds, function(ByteBufferWriter $out, array $creationMapIds) : void{
			VarInt::writeUnsignedInt($out, count($creationMapIds));
			foreach($creationMapIds as $creationMapId){
				CommonTypes::putActorUniqueId($out, $creationMapId);
			}
		});

		CommonTypes::writeOptional($out, $this->scale, Byte::writeUnsigned(...));

		CommonTypes::writeOptional($out, $this->trackedEntities, function(ByteBufferWriter $out, array $trackedEntities) : void{
			VarInt::writeUnsignedInt($out, count($trackedEntities));
			foreach($trackedEntities as $trackedEntity){
				$trackedEntity->write($out);
			}
		});

		CommonTypes::writeOptional($out, $this->decorations, function(ByteBufferWriter $out, array $decorations) : void{
			VarInt::writeUnsignedInt($out, count($decorations));
			foreach($decorations as $decoration){
				Byte::writeUnsigned($out, $decoration->getIcon());
				Byte::writeUnsigned($out, $decoration->getRotation());
				Byte::writeUnsigned($out, $decoration->getXOffset());
				Byte::writeUnsigned($out, $decoration->getYOffset());
				CommonTypes::putString($out, $decoration->getLabel());
				LE::writeUnsignedInt($out, Binary::flipIntEndianness($decoration->getColor()->toRGBA()));
			}
		});

		CommonTypes::writeOptional($out, $this->width, VarInt::writeSignedInt(...));
		CommonTypes::writeOptional($out, $this->height, VarInt::writeSignedInt(...));
		CommonTypes::writeOptional($out, $this->startX, VarInt::writeSignedInt(...));
		CommonTypes::writeOptional($out, $this->startY, VarInt::writeSignedInt(...));

		CommonTypes::writeOptional($out, $this->pixels, function(ByteBufferWriter $out, array $pixels) : void{
			VarInt::writeUnsignedInt($out, count($pixels));
			foreach($pixels as $pixel){
				LE::writeUnsignedInt($out, Binary::flipIntEndianness($pixel->toRGBA()));
			}
		});
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundMapItemData($this);
	}
}
