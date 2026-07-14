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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class ServerJoinInformation{

	public function __construct(
		private ?GatheringJoinInfo $gatheringJoinInfo,
		private ?StoreEntryPointInfo $storeEntryPointInfo,
		private ?PresenceInfo $presenceInfo,
	){}

	public function getGatheringJoinInfo() : ?GatheringJoinInfo{ return $this->gatheringJoinInfo; }

	public function getStoreEntryPointInfo() : ?StoreEntryPointInfo{ return $this->storeEntryPointInfo; }

	public function getPresenceInfo() : ?PresenceInfo{ return $this->presenceInfo; }

	public static function read(ByteBufferReader $in) : self{
		$gatheringJoinInfo = CommonTypes::readOptional($in, GatheringJoinInfo::read(...));
		$storeEntryPointInfo = CommonTypes::readOptional($in, StoreEntryPointInfo::read(...));
		$presenceInfo = CommonTypes::readOptional($in, PresenceInfo::read(...));

		return new self(
			$gatheringJoinInfo,
			$storeEntryPointInfo,
			$presenceInfo,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->gatheringJoinInfo, fn(ByteBufferWriter $out, GatheringJoinInfo $info) => $info->write($out));
		CommonTypes::writeOptional($out, $this->storeEntryPointInfo, fn(ByteBufferWriter $out, StoreEntryPointInfo $info) => $info->write($out));
		CommonTypes::writeOptional($out, $this->presenceInfo, fn(ByteBufferWriter $out, PresenceInfo $info) => $info->write($out));
	}
}
