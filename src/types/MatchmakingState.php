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

enum MatchmakingState : int{
	use PacketIntEnumTrait;

	case IDLE = 0;
	case MATCHMAKING = 1;
	case MATCH_FOUND = 2;
	case CANCELED = 3;
	case PLAYER_LEFT_PARTY = 4;
	case PLAYER_LEFT_SERVER = 5;
	case SERVER_SHUTDOWN = 6;
	case TIMED_OUT = 7;
	case REQUEUE_AS_PARTY = 8;
}
