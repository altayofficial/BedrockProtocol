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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\TransferPacket;

/**
 * Customises the loading screen the client shows while transferring to another server.
 * Every field is optional, null leaves the client default alone.
 * @see TransferPacket
 */
final class TransferPlayerUIOptions{

	public function __construct(
		private ?string $titleText = null,
		private ?string $bodyText = null,
		private ?bool $titleVisible = null,
		private ?bool $bodyVisible = null,
		private ?string $cancelButtonText = null,
		private ?bool $cancelButtonVisible = null,
		private ?float $cancelButtonDelaySeconds = null
	){}

	public function getTitleText() : ?string{ return $this->titleText; }

	public function getBodyText() : ?string{ return $this->bodyText; }

	public function getTitleVisible() : ?bool{ return $this->titleVisible; }

	public function getBodyVisible() : ?bool{ return $this->bodyVisible; }

	public function getCancelButtonText() : ?string{ return $this->cancelButtonText; }

	public function getCancelButtonVisible() : ?bool{ return $this->cancelButtonVisible; }

	/** How long the cancel button stays hidden before it shows up */
	public function getCancelButtonDelaySeconds() : ?float{ return $this->cancelButtonDelaySeconds; }

	public static function read(ByteBufferReader $in) : self{
		$titleText = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$bodyText = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$titleVisible = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$bodyVisible = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$cancelButtonText = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$cancelButtonVisible = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$cancelButtonDelaySeconds = CommonTypes::readOptional($in, LE::readFloat(...));

		return new self(
			$titleText,
			$bodyText,
			$titleVisible,
			$bodyVisible,
			$cancelButtonText,
			$cancelButtonVisible,
			$cancelButtonDelaySeconds
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->titleText, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->bodyText, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->titleVisible, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->bodyVisible, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->cancelButtonText, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->cancelButtonVisible, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->cancelButtonDelaySeconds, LE::writeFloat(...));
	}
}
