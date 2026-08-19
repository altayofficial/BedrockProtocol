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

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\types\codebuilder\CodeBuilderExecutionStatus;
use pocketmine\network\mcpe\protocol\types\codebuilder\CodeBuilderStorageQueryOptions;

class CodeBuilderSourcePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CODE_BUILDER_SOURCE_PACKET;

	public CodeBuilderStorageQueryOptions $storageQueryOptions;
	public CodeBuilderExecutionStatus $executionStatus;

	/**
	 * @generate-create-func
	 */
	public static function create(
		CodeBuilderStorageQueryOptions $storageQueryOptions,
		CodeBuilderExecutionStatus $executionStatus
	) : self{
		$result = new self;
		$result->storageQueryOptions = $storageQueryOptions;
		$result->executionStatus = $executionStatus;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->storageQueryOptions = CodeBuilderStorageQueryOptions::read($in);
		$this->executionStatus = CodeBuilderExecutionStatus::fromPacket(Byte::readUnsigned($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		$this->storageQueryOptions->write($out);
		Byte::writeUnsigned($out, $this->executionStatus->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCodeBuilderSource($this);
	}
}
