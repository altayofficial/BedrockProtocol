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

namespace pocketmine\network\mcpe\protocol\types\codebuilder;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;

final class CodeBuilderStorageQueryOptions {

	public CodeBuilderStorageQueryCategory $category;

	public CodeBuilderStorageQueryOperation $operation;

	public function __construct(
		CodeBuilderStorageQueryCategory $category,
		CodeBuilderStorageQueryOperation $operation
	){
		$this->category = $category;
		$this->operation = $operation;
	}

	public static function read(ByteBufferReader $in) : self {
		$category = CodeBuilderStorageQueryCategory::fromPacket(Byte::readUnsigned($in));
		$operation = CodeBuilderStorageQueryOperation::fromPacket(Byte::readUnsigned($in));

		return new self(
			$category,
			$operation
		);
	}

	public function write(ByteBufferWriter $out) : void {
		Byte::writeUnsigned($out, $this->category->value);
		Byte::writeUnsigned($out, $this->operation->value);
	}
}
