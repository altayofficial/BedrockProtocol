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

namespace pocketmine\network\mcpe\protocol\types\resourcepacks;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ResourcePackStackEntry{
	public function __construct(
		private string $packId,
		private string $version,
		private string $subPackName
	){}

	public function getPackId() : string{
		return $this->packId;
	}

	public function getVersion() : string{
		return $this->version;
	}

	public function getSubPackName() : string{
		return $this->subPackName;
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->packId);
		CommonTypes::putString($out, $this->version);
		CommonTypes::putString($out, $this->subPackName);
	}

	public static function read(ByteBufferReader $in) : self{
		$packId = CommonTypes::getString($in);
		$version = CommonTypes::getString($in);
		$subPackName = CommonTypes::getString($in);
		return new self($packId, $version, $subPackName);
	}
}
