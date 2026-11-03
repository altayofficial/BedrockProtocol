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

namespace pocketmine\network\mcpe\protocol\types\audio;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\ClientboundMusicPlaybackFailurePacket;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see ClientboundMusicPlaybackFailurePacket
 */
final class MusicPlaybackFailureEntry{

	public function __construct(
		private MusicPlaybackState $state,
		private string $description
	){}

	public function getState() : MusicPlaybackState{ return $this->state; }

	public function getDescription() : string{ return $this->description; }

	public static function read(ByteBufferReader $in) : self{
		$state = MusicPlaybackState::fromPacket(Byte::readUnsigned($in));
		$description = CommonTypes::getString($in);

		return new self($state, $description);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->state->value);
		CommonTypes::putString($out, $this->description);
	}
}
