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

namespace pocketmine\network\mcpe\protocol\types\skin;

class SkinAnimation{

	public const TYPE_FACE = 1;
	public const TYPE_BODY_32 = 2;
	public const TYPE_BODY_128 = 3;

	public const EXPRESSION_LINEAR = 0; //???
	public const EXPRESSION_BLINKING = 1;

	public function __construct(
		private SkinImage $image,
		private int $type,
		private float $frames,
		private int $expressionType
	){}

	/**
	 * Image of the animation.
	 */
	public function getImage() : SkinImage{
		return $this->image;
	}

	/**
	 * The type of animation you are applying.
	 */
	public function getType() : int{
		return $this->type;
	}

	/**
	 * The total amount of frames in an animation.
	 */
	public function getFrames() : float{
		return $this->frames;
	}

	public function getExpressionType() : int{ return $this->expressionType; }
}
