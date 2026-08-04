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

namespace pocketmine\network\mcpe\protocol;

use PHPUnit\Framework\TestCase;

final class ProtocolInfoTest extends TestCase{

	public function testMinecraftVersionNetwork() : void{
		self::assertMatchesRegularExpression(
			'/^(?:\d+\.)?(?:\d+\.)?(?:\d+\.)?\d+$/',
			ProtocolInfo::MINECRAFT_VERSION_NETWORK,
			"Network version should only contain 0-9 and \".\", and no more than 4 groups of digits"
		);
	}
}
