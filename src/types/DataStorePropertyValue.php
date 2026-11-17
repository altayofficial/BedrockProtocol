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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

/**
 * A node in the dynamic value tree (cereal::DynamicValue) that DataStoreChange carries. Unlike DataStoreValue,
 * this can hold nested lists and maps, which is how data driven UI screens ship their whole layout.
 *
 * @see DataStoreChange
 */
abstract class DataStorePropertyValue{
	/** hmm, the tree is recursive, so cap it before a hostile payload eats the stack */
	public const MAX_DEPTH = 32;

	abstract public function getTypeId() : int;

	abstract protected function writeValue(ByteBufferWriter $out) : void;

	final public function write(ByteBufferWriter $out) : void{
		LE::writeSignedInt($out, $this->getTypeId());
		$this->writeValue($out);
	}

	public static function read(ByteBufferReader $in, int $depth = 0) : self{
		if($depth > self::MAX_DEPTH){
			throw new PacketDecodeException("DataStore property value nested too deeply (max " . self::MAX_DEPTH . ")");
		}
		return match($type = LE::readSignedInt($in)){
			DataStorePropertyValueType::NONE => new NullDataStorePropertyValue(),
			DataStorePropertyValueType::BOOL => new BoolDataStorePropertyValue(CommonTypes::getBool($in)),
			DataStorePropertyValueType::INT64 => new IntDataStorePropertyValue(LE::readSignedLong($in)),
			DataStorePropertyValueType::DOUBLE => new DoubleDataStorePropertyValue(LE::readDouble($in)),
			DataStorePropertyValueType::STRING => new StringDataStorePropertyValue(CommonTypes::getString($in)),
			DataStorePropertyValueType::LIST => self::readList($in, $depth),
			DataStorePropertyValueType::MAP => self::readMap($in, $depth),
			default => throw new PacketDecodeException("Unknown DataStore property value type $type"),
		};
	}

	private static function readList(ByteBufferReader $in, int $depth) : ListDataStorePropertyValue{
		$values = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$values[] = self::read($in, $depth + 1);
		}
		return new ListDataStorePropertyValue($values);
	}

	private static function readMap(ByteBufferReader $in, int $depth) : MapDataStorePropertyValue{
		$values = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$key = CommonTypes::getString($in);
			$values[$key] = self::read($in, $depth + 1);
		}
		return new MapDataStorePropertyValue($values);
	}

	/**
	 * @internal
	 * @param DataStorePropertyValue[] $values
	 * @phpstan-param array<array-key, DataStorePropertyValue> $values
	 */
	protected static function writeCount(ByteBufferWriter $out, array $values) : void{
		VarInt::writeUnsignedInt($out, count($values));
	}
}
