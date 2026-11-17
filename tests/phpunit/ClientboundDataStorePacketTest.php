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

use PHPUnit\Framework\TestCase;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\types\BoolDataStorePropertyValue;
use pocketmine\network\mcpe\protocol\types\DataStoreChange;
use pocketmine\network\mcpe\protocol\types\DataStorePropertyValue;
use pocketmine\network\mcpe\protocol\types\DataStorePropertyValueType;
use pocketmine\network\mcpe\protocol\types\DataStoreRemoval;
use pocketmine\network\mcpe\protocol\types\DataStoreUpdate;
use pocketmine\network\mcpe\protocol\types\DoubleDataStorePropertyValue;
use pocketmine\network\mcpe\protocol\types\IntDataStorePropertyValue;
use pocketmine\network\mcpe\protocol\types\ListDataStorePropertyValue;
use pocketmine\network\mcpe\protocol\types\MapDataStorePropertyValue;
use pocketmine\network\mcpe\protocol\types\NullDataStorePropertyValue;
use pocketmine\network\mcpe\protocol\types\StringDataStorePropertyValue;
use pocketmine\network\mcpe\protocol\types\StringDataStoreValue;
use function str_repeat;

class ClientboundDataStorePacketTest extends TestCase{

	public function testRoundTrip() : void{
		$tree = new MapDataStorePropertyValue([
			"title" => new StringDataStorePropertyValue("Settings"),
			"layout" => new MapDataStorePropertyValue([
				"0" => new MapDataStorePropertyValue([
					"toggled" => new BoolDataStorePropertyValue(true),
					"value" => new IntDataStorePropertyValue(-5),
				]),
				"length" => new IntDataStorePropertyValue(1),
			]),
			"ratio" => new DoubleDataStorePropertyValue(0.5),
			"tags" => new ListDataStorePropertyValue([new StringDataStorePropertyValue("a"), new NullDataStorePropertyValue()]),
		]);
		$pk = ClientboundDataStorePacket::create([
			new DataStoreChange("minecraft", "custom_form_data", 3, $tree),
			new DataStoreUpdate("minecraft", "custom_form_data", "layout[0].label", new StringDataStoreValue("x"), 4, 5),
			new DataStoreRemoval("minecraft"),
		]);

		$out = new ByteBufferWriter();
		$pk->encode($out);
		$decoded = new ClientboundDataStorePacket();
		$decoded->decode(new ByteBufferReader($out->getData()));

		self::assertEquals($pk->values, $decoded->values);

		$again = new ByteBufferWriter();
		$decoded->encode($again);
		self::assertSame($out->getData(), $again->getData());
	}

	public function testDepthLimit() : void{
		$out = new ByteBufferWriter();
		//one list per level, each holding a single child
		for($i = 0; $i <= DataStorePropertyValue::MAX_DEPTH + 1; ++$i){
			LE::writeSignedInt($out, DataStorePropertyValueType::LIST);
			$out->writeByteArray("\x01");
		}
		LE::writeSignedInt($out, DataStorePropertyValueType::NONE);

		$this->expectException(PacketDecodeException::class);
		DataStorePropertyValue::read(new ByteBufferReader($out->getData()));
	}

	public function testUnknownType() : void{
		$out = new ByteBufferWriter();
		LE::writeSignedInt($out, 99);
		$out->writeByteArray(str_repeat("\x00", 8));

		$this->expectException(PacketDecodeException::class);
		DataStorePropertyValue::read(new ByteBufferReader($out->getData()));
	}
}
