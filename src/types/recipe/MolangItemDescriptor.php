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

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class MolangItemDescriptor implements ItemDescriptor{
	use GetTypeIdFromConstTrait;

	public const ID = ItemDescriptorType::MOLANG;

	public function __construct(
		private string $molangExpression,
		private int $molangVersion
	){}

	public function getMolangExpression() : string{ return $this->molangExpression; }

	public function getMolangVersion() : int{ return $this->molangVersion; }

	public static function read(ByteBufferReader $in) : self{
		$expression = CommonTypes::getString($in);
		$version = LE::readUnsignedShort($in);

		return new self($expression, $version);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->molangExpression);
		LE::writeUnsignedShort($out, $this->molangVersion);
	}
}
