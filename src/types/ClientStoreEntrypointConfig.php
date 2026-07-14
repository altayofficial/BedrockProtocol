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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see ServerStoreInfoPacket
 */
final class ClientStoreEntrypointConfig{
	public function __construct(
		private string $storeId,
		private string $storeName
	){}

	public function getStoreId() : string{ return $this->storeId; }

	public function getStoreName() : string{ return $this->storeName; }

	public static function read(ByteBufferReader $in) : self{
		$storeId = CommonTypes::getString($in);
		$storeName = CommonTypes::getString($in);

		return new self($storeId, $storeName);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->storeId);
		CommonTypes::putString($out, $this->storeName);
	}
}
