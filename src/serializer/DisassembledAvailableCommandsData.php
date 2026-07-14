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

namespace pocketmine\network\mcpe\protocol\serializer;

use pocketmine\network\mcpe\protocol\types\command\ChainedSubCommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandHardEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandSoftEnum;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumConstraintRawData;

/**
 * High-level commands data with all the info properly linked (no nasty offsets).
 */
final class DisassembledAvailableCommandsData{

	/**
	 * @param CommandData[]                                    $commandData
	 * @param string[]                                         $unusedHardEnumValues
	 * @param string[]                                         $unusedPostfixes
	 * @param CommandHardEnum[]                                $unusedHardEnums
	 * @param CommandHardEnum[]                                $unusedSoftEnums
	 * @param ChainedSubCommandData[]                          $unusedChainedSubCommandData
	 * @param string[]                                         $unusedChainedSubCommandValues
	 * @param CommandEnumConstraintRawData[]                   $repeatedEnumConstraints
	 *
	 * @phpstan-param list<CommandData>                        $commandData
	 * @phpstan-param array<int, string>                       $unusedHardEnumValues
	 * @phpstan-param array<int, string>                       $unusedPostfixes
	 * @phpstan-param array<int, CommandHardEnum>              $unusedHardEnums
	 * @phpstan-param array<int, CommandSoftEnum>              $unusedSoftEnums
	 * @phpstan-param array<int, ChainedSubCommandData>        $unusedChainedSubCommandData
	 * @phpstan-param array<int, string>                       $unusedChainedSubCommandValues
	 * @phpstan-param array<int, CommandEnumConstraintRawData> $repeatedEnumConstraints
	 */
	public function __construct(
		public readonly array $commandData,
		public readonly array $unusedHardEnumValues,
		public readonly array $unusedPostfixes,
		public readonly array $unusedHardEnums,
		public readonly array $unusedSoftEnums,
		public readonly array $unusedChainedSubCommandData,
		public readonly array $unusedChainedSubCommandValues,
		public readonly array $repeatedEnumConstraints
	){}
}
