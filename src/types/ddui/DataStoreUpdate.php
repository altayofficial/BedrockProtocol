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

namespace pocketmine\network\mcpe\protocol\types\ddui;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\ddui\update\BoolDataStoreUpdateValue;
use pocketmine\network\mcpe\protocol\types\ddui\update\DataStoreUpdateValue;
use pocketmine\network\mcpe\protocol\types\ddui\update\DataStoreUpdateValueType;
use pocketmine\network\mcpe\protocol\types\ddui\update\DoubleDataStoreUpdateValue;
use pocketmine\network\mcpe\protocol\types\ddui\update\StringDataStoreUpdateValue;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * @see ServerboundDataStorePacket
 * @see ClientboundDataStorePacket
 */
final class DataStoreUpdate implements DataStoreOperation{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreOperationType::UPDATE;

	public function __construct(
		private string $variableName,
		private DataStoreUpdateValue $data,
		private int $updateCount,
	){}

	public function getVariableName() : string{ return $this->variableName; }

	public function getData() : DataStoreUpdateValue{ return $this->data; }

	public function getUpdateCount() : int{ return $this->updateCount; }

	public static function read(ByteBufferReader $in) : self{
		$variableName = CommonTypes::getString($in);

		$data = match(VarInt::readUnsignedInt($in)){
			DataStoreUpdateValueType::DOUBLE => DoubleDataStoreUpdateValue::read($in),
			DataStoreUpdateValueType::BOOL => BoolDataStoreUpdateValue::read($in),
			DataStoreUpdateValueType::STRING => StringDataStoreUpdateValue::read($in),
			default => throw new PacketDecodeException("Unknown DataStoreValueType"),
		};

		$updateCount = LE::readUnsignedInt($in);

		return new self(
			$variableName,
			$data,
			$updateCount,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->variableName);
		VarInt::writeUnsignedInt($out, $this->data->getTypeId());
		$this->data->write($out);
		LE::writeUnsignedInt($out, $this->updateCount);
	}
}
