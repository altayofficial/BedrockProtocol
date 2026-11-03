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

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum MusicPlaybackState : int{
	use PacketIntEnumTrait;

	case LOCAL = 0;
	case ONLINE = 1;
	// server has no definition for the sound event
	case NOT_FOUND = 2;
	// server isn't ready to verify registered audio content yet
	case PENDING = 3;
}
