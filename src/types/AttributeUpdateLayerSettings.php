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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see ClientboundAttributeLayerSyncPacket
 */
final class AttributeUpdateLayerSettings extends AttributeLayerSyncPayload{
	public const ID = AttributeLayerSyncType::UPDATE_LAYER_SETTINGS;

	public function __construct(
		private string $name,
		private int $dimension,
		private AttributeLayerSettings $settings,
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getName() : string{ return $this->name; }

	public function getDimension() : int{ return $this->dimension; }

	public function getSettings() : AttributeLayerSettings{ return $this->settings; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$dimension = VarInt::readUnsignedInt($in);
		$settings = AttributeLayerSettings::read($in);

		return new self(
			$name,
			$dimension,
			$settings
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		VarInt::writeUnsignedInt($out, $this->dimension);
		$this->settings->write($out);
	}
}
