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

namespace pocketmine\network\mcpe\protocol\types\attribute;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see AttributeLayer&AttributesUpdateEnvironment
 */
final class AttributeEnvironment{

	public function __construct(
		private string $name,
		private AttributeEnvironmentPayload $payload,
	){}

	public function getName() : string{ return $this->name; }

	public function getPayload() : AttributeEnvironmentPayload{ return $this->payload; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$payload = AttributeEnvironmentPayload::read($in);

		return new self(
			$name,
			$payload
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		VarInt::writeUnsignedInt($out, $this->payload->getTypeId());
		$this->payload->write($out);
	}
}
