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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;

trait TakeOrPlaceStackRequestActionTrait{
	final public function __construct(
		private int $count,
		private ItemStackRequestSlotInfo $source,
		private ItemStackRequestSlotInfo $destination
	){}

	final public function getCount() : int{ return $this->count; }

	final public function getSource() : ItemStackRequestSlotInfo{ return $this->source; }

	final public function getDestination() : ItemStackRequestSlotInfo{ return $this->destination; }

	public static function read(ByteBufferReader $in) : self{
		$count = Byte::readUnsigned($in);
		$src = ItemStackRequestSlotInfo::read($in);
		$dst = ItemStackRequestSlotInfo::read($in);
		return new self($count, $src, $dst);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->count);
		$this->source->write($out);
		$this->destination->write($out);
	}
}
