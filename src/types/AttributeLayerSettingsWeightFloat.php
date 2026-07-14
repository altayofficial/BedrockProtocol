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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;

/**
 * @see AttributeLayerSettings
 */
final class AttributeLayerSettingsWeightFloat extends AttributeLayerSettingsWeight{
	public const ID = AttributeLayerSettingsWeightType::FLOAT;

	public function __construct(
		private float $value
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getValue() : float{ return $this->value; }

	public static function read(ByteBufferReader $in) : self{
		$value = LE::readFloat($in);

		return new self(
			$value
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->value);
	}
}
