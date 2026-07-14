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

namespace pocketmine\network\mcpe\protocol\types\camera;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function is_int;

final class CameraFovInstruction{

	/** @see CameraSetInstructionEaseType */
	private string $easeType;

	public function __construct(
		private float $fieldOfView,
		private float $easeTime,
		int|string $easeType,
		private bool $clear,
	){
		$this->easeType = is_int($easeType) ? CameraSetInstructionEaseType::toName($easeType) : $easeType;
	}

	public function getFieldOfView() : float{ return $this->fieldOfView; }

	public function getEaseTime() : float{ return $this->easeTime; }

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : string{ return $this->easeType; }

	public function getClear() : bool{ return $this->clear; }

	public static function read(ByteBufferReader $in) : self{
		$fieldOfView = LE::readFloat($in);
		$easeTime = LE::readFloat($in);
		$easeType = CommonTypes::getString($in);
		$clear = CommonTypes::getBool($in);

		return new self(
			$fieldOfView,
			$easeTime,
			$easeType,
			$clear
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->fieldOfView);
		LE::writeFloat($out, $this->easeTime);
		CommonTypes::putString($out, $this->easeType);
		CommonTypes::putBool($out, $this->clear);
	}
}
