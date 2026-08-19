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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\LessonAction;

/**
 * Handled only in Education mode. Used to fire telemetry reporting on the client.
 */
class LessonProgressPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LESSON_PROGRESS_PACKET;

	public LessonAction $lessonAction;
	public int $score;
	public string $activityId;

	/**
	 * @generate-create-func
	 */
	public static function create(LessonAction $lessonAction, int $score, string $activityId) : self{
		$result = new self;
		$result->lessonAction = $lessonAction;
		$result->score = $score;
		$result->activityId = $activityId;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->lessonAction = LessonAction::fromPacket(VarInt::readSignedInt($in));
		$this->score = VarInt::readSignedInt($in);
		$this->activityId = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->lessonAction->value);
		VarInt::writeSignedInt($out, $this->score);
		CommonTypes::putString($out, $this->activityId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLessonProgress($this);
	}
}
