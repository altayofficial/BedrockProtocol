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

enum LabTableReactionType : int{
	use PacketIntEnumTrait;

	case NONE = 0;
	case ICE_BOMB = 1;
	case BLEACH = 2;
	case ELEPHANT_TOOTHPASTE = 3;
	case FERTILIZER = 4;
	case HEAT_BLOCK = 5;
	case MAGNESIUM_SALTS = 6;
	case MISC_FIRE = 7;
	case MISC_EXPLOSION = 8;
	case MISC_LAVA = 9;
	case MISC_MYSTICAL = 10;
	case MISC_SMOKE = 11;
	case MISC_LARGE_SMOKE = 12;
}
