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

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

abstract class TransactionData{
	/** @var NetworkInventoryAction[] */
	protected array $actions = [];

	/**
	 * @return NetworkInventoryAction[]
	 */
	final public function getActions() : array{
		return $this->actions;
	}

	abstract public function getTypeId() : int;

	/**
	 * @throws DataDecodeException
	 * @throws PacketDecodeException
	 */
	final public function decode(ByteBufferReader $in) : void{
		$hasValue = CommonTypes::getBool($in);
		if($hasValue){
			$actionCount = VarInt::readUnsignedInt($in);
			for($i = 0; $i < $actionCount; ++$i){
				$this->actions[] = (new NetworkInventoryAction())->read($in);
			}
			$this->decodeData($in);
		}
	}

	/**
	 * @throws DataDecodeException
	 * @throws PacketDecodeException
	 */
	abstract protected function decodeData(ByteBufferReader $in) : void;

	final public function encode(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $hasValue = count($this->actions) > 0);
		if($hasValue){
			VarInt::writeUnsignedInt($out, count($this->actions));
			foreach($this->actions as $action){
				$action->write($out);
			}
			$this->encodeData($out);
		}
	}

	abstract protected function encodeData(ByteBufferWriter $out) : void;
}
