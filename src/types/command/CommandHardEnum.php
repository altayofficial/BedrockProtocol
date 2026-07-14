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

namespace pocketmine\network\mcpe\protocol\types\command;

class CommandHardEnum{
	/**
	 * @param string[]|ConstrainedEnumValue[]           $values
	 * @phpstan-param list<string|ConstrainedEnumValue> $values
	 */
	public function __construct(
		private string $name,
		private array $values,
	){
		self::valuesCheck(...$this->values);
	}

	private static function valuesCheck(string|ConstrainedEnumValue ...$v) : void{
		//NOOP
	}

	public function getName() : string{
		return $this->name;
	}

	/**
	 * @return string[]|ConstrainedEnumValue[]
	 * @phpstan-return list<string|ConstrainedEnumValue>
	 */
	public function getValues() : array{
		return $this->values;
	}
}
