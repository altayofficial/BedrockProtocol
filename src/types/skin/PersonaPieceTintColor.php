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

namespace pocketmine\network\mcpe\protocol\types\skin;

use function count;

final class PersonaPieceTintColor{

	public const PIECE_TYPE_PERSONA_EYES = "persona_eyes";
	public const PIECE_TYPE_PERSONA_HAIR = "persona_hair";
	public const PIECE_TYPE_PERSONA_MOUTH = "persona_mouth";

	public const COLOR_COUNT = 4;

	/**
	 * @param int[] $colors
	 * @phpstan-param list<int> $colors
	 */
	public function __construct(
		private string $pieceType,
		private array $colors
	){
		if(count($colors) !== self::COLOR_COUNT){
			throw new \InvalidArgumentException("Expected exactly " . self::COLOR_COUNT . " colors, got " . count($colors));
		}
	}

	public function getPieceType() : string{
		return $this->pieceType;
	}

	/**
	 * @return int[]
	 * @phpstan-return list<int>
	 */
	public function getColors() : array{
		return $this->colors;
	}
}
