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
use pmmp\encoding\VarInt;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GraphicsOverrideParameterType;
use pocketmine\network\mcpe\protocol\types\ParameterKeyframeValue;
use function count;

class GraphicsOverrideParameterPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::GRAPHICS_OVERRIDE_PARAMETER_PACKET;

	/** @var ParameterKeyframeValue[] */
	private array $parameterKeyframeValues = [];
	private ?float $floatValue;
	private ?Vector3 $vec3Value; //BLAMEMOJANG: bad naming, but let's do it for now
	private string $biomeIdentifier;
	private ?string $playerIdentifier;
	private GraphicsOverrideParameterType $parameterType;
	private bool $resetParameter;

	/**
	 * @param ParameterKeyframeValue[]      $parameterKeyframeValues
	 */
	public static function create(
		array $parameterKeyframeValues,
		?float $floatValue,
		?Vector3 $vec3Value,
		string $biomeIdentifier,
		?string $playerIdentifier,
		GraphicsOverrideParameterType $parameterType,
		bool $resetParameter,
	) : self{
		$result = new self;
		$result->parameterKeyframeValues = $parameterKeyframeValues;
		$result->floatValue = $floatValue;
		$result->vec3Value = $vec3Value;
		$result->biomeIdentifier = $biomeIdentifier;
		$result->playerIdentifier = $playerIdentifier;
		$result->parameterType = $parameterType;
		$result->resetParameter = $resetParameter;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$count = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $count; ++$i){
			$this->parameterKeyframeValues[] = ParameterKeyframeValue::read($in);
		}
		$this->floatValue = CommonTypes::readOptional($in, LE::readFloat(...));
		$this->vec3Value = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$this->biomeIdentifier = CommonTypes::getString($in);
		$this->playerIdentifier = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$this->parameterType = GraphicsOverrideParameterType::fromPacket(Byte::readUnsigned($in));
		$this->resetParameter = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->parameterKeyframeValues));
		foreach($this->parameterKeyframeValues as $value){
			$value->write($out);
		}
		CommonTypes::writeOptional($out, $this->floatValue, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->vec3Value, CommonTypes::putVector3(...));
		CommonTypes::putString($out, $this->biomeIdentifier);
		CommonTypes::writeOptional($out, $this->playerIdentifier, CommonTypes::putString(...));
		Byte::writeUnsigned($out, $this->parameterType->value);
		CommonTypes::putBool($out, $this->resetParameter);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleGraphicsOverrideParameter($this);
	}
}
