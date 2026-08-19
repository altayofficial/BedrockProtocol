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

enum LegacyTelemetryEventType : int{
	use PacketIntEnumTrait;

	case ACHIEVEMENT = 0;
	case INTERACTION = 1;
	case PORTAL_CREATED = 2;
	case PORTAL_USED = 3;
	case MOB_KILLED = 4;
	case CAULDRON_USED = 5;
	case PLAYER_DIED = 6;
	case BOSS_KILLED = 7;
	case AGENT_COMMAND_OBSOLETE = 8;
	case AGENT_CREATED = 9;
	case PATTERN_REMOVED_OBSOLETE = 10;
	case SLASH_COMMAND = 11;
	case FISH_BUCKETED_OBSOLETE = 12;
	case MOB_BORN = 13;
	case PET_DIED_OBSOLETE = 14;
	case POI_CAULDRON_USED = 15;
	case COMPOSTER_USED = 16;
	case BELL_USED = 17;
	case ACTOR_DEFINITION = 18;
	case RAID_UPDATE = 19;
	case PLAYER_MOVEMENT_ANOMALY_OBSOLETE = 20;
	case PLAYER_MOVEMENT_CORRECTED_OBSOLETE = 21;
	case HONEY_HARVESTED = 22;
	case TARGET_BLOCK_HIT = 23;
	case PIGLIN_BARTER = 24;
	case PLAYER_WAXED_OR_UNWAXED_COPPER = 25;
	case CODE_BUILDER_RUNTIME_ACTION = 26;
	case CODE_BUILDER_SCOREBOARD = 27;
	case STRIDER_RIDDEN_IN_LAVA_IN_OVERWORLD = 28;
	case SNEAK_CLOSE_TO_SCULK_SENSOR = 29;
	case CAREFUL_RESTORATION = 30;
	case ITEM_USED = 31;
}
