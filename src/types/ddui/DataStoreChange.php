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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValue;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueType;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * @see ClientboundDataStorePacket
 */
final class DataStoreChange implements DataStoreOperation{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreOperationType::CHANGE;

	public function __construct(
		private string $variableName,
		private int $updateCount,
		private ?DynamicValue $data
	){}

	public function getVariableName() : string{ return $this->variableName; }

	public function getUpdateCount() : int{ return $this->updateCount; }

	public function getData() : ?DynamicValue{ return $this->data; }

	public static function read(ByteBufferReader $in) : self{
		$variableName = CommonTypes::getString($in);
		$updateCount = LE::readUnsignedInt($in);

		$type = LE::readUnsignedInt($in);
		$data = DynamicValue::read($in, $type);

		return new self(
			$variableName,
			$updateCount,
			$data,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->variableName);
		LE::writeUnsignedInt($out, $this->updateCount);

		//TODO: yucky, we really need to revamp how unions are handled :(
		$type = $this->data?->getTypeId() ?? DynamicValueType::NULL;
		LE::writeUnsignedInt($out, $type);
		$this->data?->write($out);
	}
}
