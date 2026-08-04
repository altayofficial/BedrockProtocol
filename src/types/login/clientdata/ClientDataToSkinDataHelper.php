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

namespace pocketmine\network\mcpe\protocol\types\login\clientdata;

use pocketmine\network\mcpe\protocol\types\skin\PersonaPieceTintColor;
use pocketmine\network\mcpe\protocol\types\skin\PersonaSkinPiece;
use pocketmine\network\mcpe\protocol\types\skin\SkinAnimation;
use pocketmine\network\mcpe\protocol\types\skin\SkinData;
use pocketmine\network\mcpe\protocol\types\skin\SkinImage;
use Ramsey\Uuid\Uuid;
use function array_map;
use function array_slice;
use function array_values;
use function base64_decode;
use function count;
use function hexdec;
use function ltrim;

final class ClientDataToSkinDataHelper{

	private const PIECE_TYPE_MAP = [
		"persona_skeleton" => PersonaSkinPiece::PIECE_TYPE_SKELETON,
		"persona_body" => PersonaSkinPiece::PIECE_TYPE_BODY,
		"persona_skin" => PersonaSkinPiece::PIECE_TYPE_SKIN,
		"persona_bottom" => PersonaSkinPiece::PIECE_TYPE_BOTTOM,
		"persona_feet" => PersonaSkinPiece::PIECE_TYPE_FEET,
		"persona_dress" => PersonaSkinPiece::PIECE_TYPE_DRESS,
		"persona_top" => PersonaSkinPiece::PIECE_TYPE_TOP,
		"persona_high_pants" => PersonaSkinPiece::PIECE_TYPE_HIGH_PANTS,
		"persona_hand" => PersonaSkinPiece::PIECE_TYPE_HANDS, // WHAT THE FUCK
		"persona_outerwear" => PersonaSkinPiece::PIECE_TYPE_OUTERWEAR,
		"persona_facial_hair" => PersonaSkinPiece::PIECE_TYPE_FACIAL_HAIR,
		"persona_mouth" => PersonaSkinPiece::PIECE_TYPE_MOUTH,
		"persona_eyes" => PersonaSkinPiece::PIECE_TYPE_EYES,
		"persona_hair" => PersonaSkinPiece::PIECE_TYPE_HAIR,
		"persona_hood" => PersonaSkinPiece::PIECE_TYPE_HOOD,
		"persona_back" => PersonaSkinPiece::PIECE_TYPE_BACK,
		"persona_face_accessory" => PersonaSkinPiece::PIECE_TYPE_FACE_ACCESSORY,
		"persona_head" => PersonaSkinPiece::PIECE_TYPE_HEAD,
		"persona_legs" => PersonaSkinPiece::PIECE_TYPE_LEGS,
		"persona_left_leg" => PersonaSkinPiece::PIECE_TYPE_LEFT_LEG,
		"persona_right_leg" => PersonaSkinPiece::PIECE_TYPE_RIGHT_LEG,
		"persona_arms" => PersonaSkinPiece::PIECE_TYPE_ARMS,
		"persona_left_arm" => PersonaSkinPiece::PIECE_TYPE_LEFT_ARM,
		"persona_right_arm" => PersonaSkinPiece::PIECE_TYPE_RIGHT_ARM,
		"persona_capes" => PersonaSkinPiece::PIECE_TYPE_CAPES,
		"persona_classic_skin" => PersonaSkinPiece::PIECE_TYPE_CLASSIC_SKIN,
		"persona_emote" => PersonaSkinPiece::PIECE_TYPE_EMOTE,
	];

	/**
	 * @throws \InvalidArgumentException
	 */
	private static function safeB64Decode(string $base64, string $context) : string{
		$result = base64_decode($base64, true);
		if($result === false){
			throw new \InvalidArgumentException("$context: Malformed base64, cannot be decoded");
		}
		return $result;
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	private static function convertArmSize(string $armSize) : int{
		return match($armSize){
			"slim" => SkinData::ARM_SIZE_SLIM,
			"wide", "" => SkinData::ARM_SIZE_WIDE,
			default => throw new \InvalidArgumentException("Unknown arm size \"$armSize\"")
		};
	}

	private static function convertColor(string $color) : int{
		return (int) hexdec(ltrim($color, "#"));
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	private static function convertPieceType(string $pieceType) : int{
		return self::PIECE_TYPE_MAP[$pieceType] ?? throw new \InvalidArgumentException("Unknown persona piece type \"$pieceType\"");
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	public static function fromClientData(ClientData $clientData) : SkinData{
		/** @var SkinAnimation[] $animations */
		$animations = [];
		foreach($clientData->AnimatedImageData as $k => $animation){
			$animations[] = new SkinAnimation(
				new SkinImage(
					$animation->ImageHeight,
					$animation->ImageWidth,
					self::safeB64Decode($animation->Image, "AnimatedImageData.$k.Image")
				),
				$animation->Type,
				$animation->Frames,
				$animation->AnimationExpression
			);
		}
		return new SkinData(
			$clientData->SkinId,
			"",
			self::safeB64Decode($clientData->SkinResourcePatch, "SkinResourcePatch"),
			new SkinImage($clientData->SkinImageHeight, $clientData->SkinImageWidth, self::safeB64Decode($clientData->SkinData, "SkinData")),
			$animations,
			new SkinImage($clientData->CapeImageHeight, $clientData->CapeImageWidth, self::safeB64Decode($clientData->CapeData, "CapeData")),
			self::safeB64Decode($clientData->SkinGeometryData, "SkinGeometryData"),
			self::safeB64Decode($clientData->SkinGeometryDataEngineVersion, "SkinGeometryDataEngineVersion"), //yes, they actually base64'd the version!
			self::safeB64Decode($clientData->SkinAnimationData, "SkinAnimationData"),
			$clientData->CapeId,
			null,
			self::convertArmSize($clientData->ArmSize),
			self::convertColor($clientData->SkinColor),
			array_map(function(ClientDataPersonaSkinPiece $piece) : PersonaSkinPiece{
				return new PersonaSkinPiece($piece->PieceId, self::convertPieceType($piece->PieceType), Uuid::fromString($piece->PackId), $piece->IsDefault, $piece->ProductId);
			}, $clientData->PersonaPieces),
			array_map(function(ClientDataPersonaPieceTintColor $tint) : PersonaPieceTintColor{
				$colors = [];
				foreach(array_slice(array_values($tint->Colors), 0, PersonaPieceTintColor::COLOR_COUNT) as $color){
					$colors[] = self::convertColor($color);
				}
				while(count($colors) < PersonaPieceTintColor::COLOR_COUNT){
					$colors[] = 0;
				}
				return new PersonaPieceTintColor($tint->PieceType, $colors);
			}, $clientData->PieceTintColors),
			true,
			$clientData->PremiumSkin,
			$clientData->PersonaSkin,
			$clientData->CapeOnClassicSkin,
			true, //assume this is true? there's no field for it ...
			$clientData->OverrideSkin ?? true,
		);
	}
}
