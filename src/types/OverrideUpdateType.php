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

/**
 * @see PlayerUpdateEntityOverridesPacket
 */
enum OverrideUpdateType : int{
	use PacketIntEnumTrait;

	case CLEAR_OVERRIDES = 0;
	case REMOVE_OVERRIDE = 1;
	case SET_INT_OVERRIDE = 2;
	case SET_FLOAT_OVERRIDE = 3;

	public function getId() : string{
		return match($this){
			self::CLEAR_OVERRIDES => "clearoverrides",
			self::REMOVE_OVERRIDE => "removeoverride",
			self::SET_INT_OVERRIDE => "setintoverride",
			self::SET_FLOAT_OVERRIDE => "setfloatoverride",
		};
	}
}
