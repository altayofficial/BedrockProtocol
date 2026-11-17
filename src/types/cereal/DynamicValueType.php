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

namespace pocketmine\network\mcpe\protocol\types\cereal;

/**
 * These values aren't present in the spec.
 * As of 1.26.30, these were obtained from BDS symbols for the following types:
 *
 * Bedrock::DDUI::DataStoreChange
 * cereal::DynamicValue
 *
 * The positions of the types in the std::variant used by cereal::DynamicValue are the values of this enum.
 *
 * Since cereal::DynamicValue appears non-specific to DDUI, it's possible this type may appear elsewhere in the protocol
 * in the future.
 */
final class DynamicValueType{
	public const NULL = 0;
	public const BOOL = 1;
	public const LONG = 2;
	public const DOUBLE = 3;
	public const STRING = 4;
	public const LIST = 5;
	public const MAP = 6;
}
