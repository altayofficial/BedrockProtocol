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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\inventory\InventoryTransactionChangedSlotsHack;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemTransactionData;
use function count;

final class ItemInteractionData{
	/**
	 * @param InventoryTransactionChangedSlotsHack[] $requestChangedSlots
	 */
	public function __construct(
		private int $requestId,
		private array $requestChangedSlots,
		private UseItemTransactionData $transactionData
	){}

	public function getRequestId() : int{
		return $this->requestId;
	}

	/**
	 * @return InventoryTransactionChangedSlotsHack[]
	 */
	public function getRequestChangedSlots() : array{
		return $this->requestChangedSlots;
	}

	public function getTransactionData() : UseItemTransactionData{
		return $this->transactionData;
	}

	/**
	 * Changed slots are only present for legacy requests, which are identified by a negative even request ID.
	 */
	private static function hasChangedSlots(int $requestId) : bool{
		return $requestId < -1 && ($requestId & 1) === 0;
	}

	public static function read(ByteBufferReader $in) : self{
		$requestId = VarInt::readSignedInt($in);
		$requestChangedSlots = [];
		if(self::hasChangedSlots($requestId)){
			$len = VarInt::readUnsignedInt($in);
			for($i = 0; $i < $len; ++$i){
				$requestChangedSlots[] = InventoryTransactionChangedSlotsHack::read($in);
			}
		}
		$transactionData = new UseItemTransactionData();
		$transactionData->decodeFromItemInteraction($in);
		return new ItemInteractionData($requestId, $requestChangedSlots, $transactionData);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->requestId);
		$hasChangedSlots = self::hasChangedSlots($this->requestId);
		if($hasChangedSlots){
			VarInt::writeUnsignedInt($out, count($this->requestChangedSlots));
			foreach($this->requestChangedSlots as $changedSlot){
				$changedSlot->write($out);
			}
		}
		$this->transactionData->encodeForItemInteraction($out);
	}
}
