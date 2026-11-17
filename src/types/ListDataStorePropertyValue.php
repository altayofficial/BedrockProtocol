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

use pmmp\encoding\ByteBufferWriter;

final class ListDataStorePropertyValue extends DataStorePropertyValue{
	public const ID = DataStorePropertyValueType::LIST;

	/**
	 * @param DataStorePropertyValue[] $values
	 * @phpstan-param list<DataStorePropertyValue> $values
	 */
	public function __construct(
		private readonly array $values
	){}

	/**
	 * @return DataStorePropertyValue[]
	 * @phpstan-return list<DataStorePropertyValue>
	 */
	public function getValues() : array{ return $this->values; }

	public function getTypeId() : int{
		return self::ID;
	}

	protected function writeValue(ByteBufferWriter $out) : void{
		self::writeCount($out, $this->values);
		foreach($this->values as $value){
			$value->write($out);
		}
	}
}
