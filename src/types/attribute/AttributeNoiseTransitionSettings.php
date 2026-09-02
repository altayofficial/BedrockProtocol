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
 * @see AttributeEnvironmentPayloadNoiseTransition
 */
final class AttributeNoiseTransitionSettings{

	public function __construct(
		private int $totalTransitionTicks,
		private int $currentTransitionTicks,
		private int $easeType,
		private string $clockName,
		private int $localTransitionTicks,
		private string $noiseName,
		private NoiseAlignment $noiseAlignment,
	){}

	public function getTotalTransitionTicks() : int{ return $this->totalTransitionTicks; }

	public function getCurrentTransitionTicks() : int{ return $this->currentTransitionTicks; }

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : int{ return $this->easeType; }

	public function getClockName() : string{ return $this->clockName; }

	public function getLocalTransitionTicks() : int{ return $this->localTransitionTicks; }

	public function getNoiseName() : string{ return $this->noiseName; }

	public function getNoiseAlignment() : NoiseAlignment{ return $this->noiseAlignment; }

	public static function read(ByteBufferReader $in) : self{
		$totalTransitionTicks = VarInt::readUnsignedInt($in);
		$currentTransitionTicks = VarInt::readUnsignedInt($in);
		$easeType = VarInt::readUnsignedInt($in);
		$clockName = CommonTypes::getString($in);
		$localTransitionTicks = VarInt::readUnsignedInt($in);
		$noiseName = CommonTypes::getString($in);
		$noiseAlignment = NoiseAlignment::read($in);

		return new self(
			$totalTransitionTicks,
			$currentTransitionTicks,
			$easeType,
			$clockName,
			$localTransitionTicks,
			$noiseName,
			$noiseAlignment
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->totalTransitionTicks);
		VarInt::writeUnsignedInt($out, $this->currentTransitionTicks);
		VarInt::writeUnsignedInt($out, $this->easeType);
		CommonTypes::putString($out, $this->clockName);
		VarInt::writeUnsignedInt($out, $this->localTransitionTicks);
		CommonTypes::putString($out, $this->noiseName);
		$this->noiseAlignment->write($out);
	}
}
