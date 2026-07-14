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

use pocketmine\math\Vector3;

class StructureSettings{

	public string $paletteName;
	public bool $ignoreEntities;
	public bool $ignoreBlocks;
	public bool $allowNonTickingChunks;
	public BlockPosition $dimensions;
	public BlockPosition $offset;
	public int $lastTouchedByPlayerID;
	public int $rotation;
	public int $mirror;
	public int $animationMode;
	public float $animationSeconds;
	public float $integrityValue;
	public int $integritySeed;
	public Vector3 $pivot;
}
