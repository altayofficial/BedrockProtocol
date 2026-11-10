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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\CompressionAlgorithm;
use pocketmine\network\mcpe\protocol\types\SctpRecoveryProfile;

/**
 * This is the first packet sent by the server in a game session, in response to a network settings request (only if
 * protocol versions are a match). It includes values for things like which compression algorithm to use, size threshold
 * for compressing packets, and more.
 */
class NetworkSettingsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::NETWORK_SETTINGS_PACKET;

	public const COMPRESS_NOTHING = 0;
	public const COMPRESS_EVERYTHING = 1;

	private int $compressionThreshold;
	private int $compressionAlgorithm;
	private bool $enableClientThrottling;
	private int $clientThrottleThreshold;
	private float $clientThrottleScalar;
	private SctpRecoveryProfile $sctpRecoveryProfile;

	/**
	 * @generate-create-func
	 */
	public static function create(int $compressionThreshold, int $compressionAlgorithm, bool $enableClientThrottling, int $clientThrottleThreshold, float $clientThrottleScalar, SctpRecoveryProfile $sctpRecoveryProfile) : self{
		$result = new self;
		$result->compressionThreshold = $compressionThreshold;
		$result->compressionAlgorithm = $compressionAlgorithm;
		$result->enableClientThrottling = $enableClientThrottling;
		$result->clientThrottleThreshold = $clientThrottleThreshold;
		$result->clientThrottleScalar = $clientThrottleScalar;
		$result->sctpRecoveryProfile = $sctpRecoveryProfile;
		return $result;
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	public function getCompressionThreshold() : int{
		return $this->compressionThreshold;
	}

	/**
	 * @see CompressionAlgorithm
	 */
	public function getCompressionAlgorithm() : int{ return $this->compressionAlgorithm; }

	public function isEnableClientThrottling() : bool{ return $this->enableClientThrottling; }

	public function getClientThrottleThreshold() : int{ return $this->clientThrottleThreshold; }

	public function getClientThrottleScalar() : float{ return $this->clientThrottleScalar; }

	public function getSctpRecoveryProfile() : SctpRecoveryProfile{ return $this->sctpRecoveryProfile; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->compressionThreshold = LE::readUnsignedShort($in);
		$this->compressionAlgorithm = LE::readUnsignedShort($in);
		$this->enableClientThrottling = CommonTypes::getBool($in);
		$this->clientThrottleThreshold = Byte::readUnsigned($in);
		$this->clientThrottleScalar = LE::readFloat($in);
		// client rejects the packet on unknown values, so we do too
		$this->sctpRecoveryProfile = SctpRecoveryProfile::fromPacket(Byte::readUnsigned($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedShort($out, $this->compressionThreshold);
		LE::writeUnsignedShort($out, $this->compressionAlgorithm);
		CommonTypes::putBool($out, $this->enableClientThrottling);
		Byte::writeUnsigned($out, $this->clientThrottleThreshold);
		LE::writeFloat($out, $this->clientThrottleScalar);
		Byte::writeUnsigned($out, $this->sctpRecoveryProfile->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleNetworkSettings($this);
	}
}
