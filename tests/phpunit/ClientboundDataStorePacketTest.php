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
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueBool;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueDouble;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueList;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueLong;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueMap;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueString;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreChange;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreRemoval;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreUpdate;
use pocketmine\network\mcpe\protocol\types\ddui\update\StringDataStoreUpdateValue;

class ClientboundDataStorePacketTest extends TestCase{

	public function testRoundTrip() : void{
		$tree = new DynamicValueMap([
			"title" => new DynamicValueString("Settings"),
			"layout" => new DynamicValueMap([
				"first" => new DynamicValueMap([
					"toggled" => new DynamicValueBool(true),
					"value" => new DynamicValueLong(-5),
				]),
				"length" => new DynamicValueLong(1),
			]),
			"ratio" => new DynamicValueDouble(0.5),
			"tags" => new DynamicValueList([new DynamicValueString("a"), null]),
			"nothing" => null,
		]);
		$pk = ClientboundDataStorePacket::create([
			new DataStoreChange("custom_form_data_0", 3, $tree),
			new DataStoreUpdate("custom_form_data_0", new StringDataStoreUpdateValue("x"), 4),
			new DataStoreRemoval("minecraft"),
		]);

		$out = new ByteBufferWriter();
		$pk->encode($out);
		$decoded = new ClientboundDataStorePacket();
		$decoded->decode(new ByteBufferReader($out->getData()));

		$again = new ByteBufferWriter();
		$decoded->encode($again);
		self::assertSame($out->getData(), $again->getData());
	}
}
