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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class CommandSoftEnum{

	/**
	 * @param string[] $values
	 * @phpstan-param list<string> $values
	 */
	public function __construct(
		private string $name,
		private array $values
	){
		self::stringArrayCheck(...$this->values);
	}

	private static function stringArrayCheck(string ...$values) : void{
		//NOOP
	}

	public function getName() : string{ return $this->name; }

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getValues() : array{ return $this->values; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);

		$values = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$values[] = CommonTypes::getString($in);
		}

		return new self($name, $values);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);

		VarInt::writeUnsignedInt($out, count($this->values));
		foreach($this->values as $value){
			CommonTypes::putString($out, $value);
		}
	}
}
