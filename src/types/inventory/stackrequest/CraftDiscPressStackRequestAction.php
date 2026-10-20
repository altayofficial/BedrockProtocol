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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Sent when the player presses a disc in the disc press, the track is picked by its sound event id.
 */
final class CraftDiscPressStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_DISC_PRESS;

	public function __construct(
		private string $trackSoundEventId,
		private int $numCrafts
	){}

	public function getTrackSoundEventId() : string{ return $this->trackSoundEventId; }

	public function getNumCrafts() : int{ return $this->numCrafts; }

	public static function read(ByteBufferReader $in) : self{
		$trackSoundEventId = CommonTypes::getString($in);
		$numCrafts = Byte::readUnsigned($in);
		return new self($trackSoundEventId, $numCrafts);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->trackSoundEventId);
		Byte::writeUnsigned($out, $this->numCrafts);
	}
}
