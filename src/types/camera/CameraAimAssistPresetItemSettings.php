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

namespace pocketmine\network\mcpe\protocol\types\camera;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CameraAimAssistPresetItemSettings{

	public function __construct(
		private string $itemIdentifier,
		private string $categoryName,
	){}

	public function getItemIdentifier() : string{ return $this->itemIdentifier; }

	public function getCategoryName() : string{ return $this->categoryName; }

	public static function read(ByteBufferReader $in) : self{
		$itemIdentifier = CommonTypes::getString($in);
		$categoryName = CommonTypes::getString($in);
		return new self(
			$itemIdentifier,
			$categoryName
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->itemIdentifier);
		CommonTypes::putString($out, $this->categoryName);
	}
}
