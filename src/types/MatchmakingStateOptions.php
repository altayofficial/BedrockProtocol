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
use pocketmine\network\mcpe\protocol\ClientboundMatchmakingStatePacket;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see ClientboundMatchmakingStatePacket
 */
final class MatchmakingStateOptions{

	public function __construct(
		private ?string $triggeringPlayerName,
		private ?bool $triggeredByLocalPlayer
	){}

	public function getTriggeringPlayerName() : ?string{ return $this->triggeringPlayerName; }

	public function getTriggeredByLocalPlayer() : ?bool{ return $this->triggeredByLocalPlayer; }

	public static function read(ByteBufferReader $in) : self{
		$triggeringPlayerName = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$triggeredByLocalPlayer = CommonTypes::readOptional($in, CommonTypes::getBool(...));

		return new self(
			$triggeringPlayerName,
			$triggeredByLocalPlayer
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->triggeringPlayerName, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->triggeredByLocalPlayer, CommonTypes::putBool(...));
	}
}
