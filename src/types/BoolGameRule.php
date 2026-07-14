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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class BoolGameRule extends GameRule{
	use GetTypeIdFromConstTrait;

	public const ID = GameRuleType::BOOL;

	private bool $value;

	public function __construct(bool $value, bool $isPlayerModifiable){
		parent::__construct($isPlayerModifiable);
		$this->value = $value;
	}

	public function getValue() : bool{
		return $this->value;
	}

	public function encode(ByteBufferWriter $out, bool $isStartGame) : void{
		CommonTypes::putBool($out, $this->value);
	}

	public static function decode(ByteBufferReader $in, bool $isPlayerModifiable) : self{
		return new self(CommonTypes::getBool($in), $isPlayerModifiable);
	}
}
