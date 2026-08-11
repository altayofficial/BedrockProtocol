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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class DeathCauseMessageType{

	public string $deathCauseAttackName; // BLAMEMOJANG: bad variable name, what is this bro

	/** @var string[] */
	public array $deathCauseMessageList;

	/**
	 * @param string[]  $deathCauseMessageList
	 */
	public function __construct(
		string $deathCauseAttackName,
		array $deathCauseMessageList
	){
		$this->deathCauseAttackName = $deathCauseAttackName;
		$this->deathCauseMessageList = $deathCauseMessageList;
	}

	public static function read(ByteBufferReader $in) : self {
		$deathCauseAttackName = CommonTypes::getString($in);

		$deathCauseMessageList = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; $i++){
			$deathCauseMessageList[] = CommonTypes::getString($in);
		}

		return new self(
			$deathCauseAttackName,
			$deathCauseMessageList
		);
	}

	public function write(ByteBufferWriter $out) : void {
		CommonTypes::putString($out, $this->deathCauseAttackName);

		VarInt::writeUnsignedInt($out, count($this->deathCauseMessageList));
		foreach($this->deathCauseMessageList as $parameter){
			CommonTypes::putString($out, $parameter);
		}
	}
}
