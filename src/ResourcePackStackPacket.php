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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\Experiments;
use pocketmine\network\mcpe\protocol\types\resourcepacks\EditorPackTypes;
use pocketmine\network\mcpe\protocol\types\resourcepacks\ResourcePackStackEntry;
use function count;

class ResourcePackStackPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_STACK_PACKET;

	/** @var ResourcePackStackEntry[] */
	public array $resourcePackStack = [];
	public bool $mustAccept = false;
	public string $baseGameVersion = ProtocolInfo::MINECRAFT_VERSION_NETWORK;
	public Experiments $experiments;
	/** Bitmask of EditorPackTypes flags */
	public int $editorPackTypes = EditorPackTypes::NONE;

	/**
	 * @generate-create-func
	 * @param ResourcePackStackEntry[] $resourcePackStack
	 */
	public static function create(array $resourcePackStack, bool $mustAccept, string $baseGameVersion, Experiments $experiments, int $editorPackTypes) : self{
		$result = new self;
		$result->resourcePackStack = $resourcePackStack;
		$result->mustAccept = $mustAccept;
		$result->baseGameVersion = $baseGameVersion;
		$result->experiments = $experiments;
		$result->editorPackTypes = $editorPackTypes;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->mustAccept = CommonTypes::getBool($in);

		$resourcePackCount = VarInt::readUnsignedInt($in);
		while($resourcePackCount-- > 0){
			$this->resourcePackStack[] = ResourcePackStackEntry::read($in);
		}

		$this->baseGameVersion = CommonTypes::getString($in);
		$this->experiments = Experiments::read($in);
		$this->editorPackTypes = Byte::readUnsigned($in);
		if(($this->editorPackTypes & ~EditorPackTypes::ALL) !== 0){
			throw new PacketDecodeException("Invalid editor pack types flags: $this->editorPackTypes");
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->mustAccept);

		VarInt::writeUnsignedInt($out, count($this->resourcePackStack));
		foreach($this->resourcePackStack as $entry){
			$entry->write($out);
		}

		CommonTypes::putString($out, $this->baseGameVersion);
		$this->experiments->write($out);
		Byte::writeUnsigned($out, $this->editorPackTypes);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleResourcePackStack($this);
	}
}
