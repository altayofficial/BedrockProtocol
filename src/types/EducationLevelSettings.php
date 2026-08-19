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
use pmmp\encoding\DataDecodeException;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class EducationLevelSettings{

	public string $codeBuilderDefaultUri;
	public string $codeBuilderTitle;
	public bool $canResizeCodeBuilder;
	public bool $disableLegacyTitleBar;
	public string $postProcessFilter;
	public string $screenshotBorderResourcePath;
	public ?EducationSettingsAgentCapabilities $agentCapabilities;
	public ?string $codeBuilderOverrideUri;
	public bool $hasQuiz;
	public ?EducationSettingsExternalLinkSettings $linkSettings;

	public function __construct(
		string $codeBuilderDefaultUri,
		string $codeBuilderTitle,
		bool $canResizeCodeBuilder,
		bool $disableLegacyTitleBar,
		string $postProcessFilter,
		string $screenshotBorderResourcePath,
		?EducationSettingsAgentCapabilities $agentCapabilities,
		?string $codeBuilderOverrideUri,
		bool $hasQuiz,
		?EducationSettingsExternalLinkSettings $linkSettings
	){
		$this->codeBuilderDefaultUri = $codeBuilderDefaultUri;
		$this->codeBuilderTitle = $codeBuilderTitle;
		$this->canResizeCodeBuilder = $canResizeCodeBuilder;
		$this->disableLegacyTitleBar = $disableLegacyTitleBar;
		$this->postProcessFilter = $postProcessFilter;
		$this->screenshotBorderResourcePath = $screenshotBorderResourcePath;
		$this->agentCapabilities = $agentCapabilities;
		$this->codeBuilderOverrideUri = $codeBuilderOverrideUri;
		$this->hasQuiz = $hasQuiz;
		$this->linkSettings = $linkSettings;
	}

	/**
	 * @throws DataDecodeException
	 * @throws PacketDecodeException
	 */
	public static function read(ByteBufferReader $in) : self{
		$codeBuilderDefaultUri = CommonTypes::getString($in);
		$codeBuilderTitle = CommonTypes::getString($in);
		$canResizeCodeBuilder = CommonTypes::getBool($in);
		$disableLegacyTitleBar = CommonTypes::getBool($in);
		$postProcessFilter = CommonTypes::getString($in);
		$screenshotBorderResourcePath = CommonTypes::getString($in);
		$agentCapabilities = CommonTypes::readOptional($in, EducationSettingsAgentCapabilities::read(...));
		$codeBuilderOverrideUri = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$hasQuiz = CommonTypes::getBool($in);
		$linkSettings = CommonTypes::readOptional($in, EducationSettingsExternalLinkSettings::read(...));

		return new self(
			$codeBuilderDefaultUri,
			$codeBuilderTitle,
			$canResizeCodeBuilder,
			$disableLegacyTitleBar,
			$postProcessFilter,
			$screenshotBorderResourcePath,
			$agentCapabilities,
			$codeBuilderOverrideUri,
			$hasQuiz,
			$linkSettings
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->codeBuilderDefaultUri);
		CommonTypes::putString($out, $this->codeBuilderTitle);
		CommonTypes::putBool($out, $this->canResizeCodeBuilder);
		CommonTypes::putBool($out, $this->disableLegacyTitleBar);
		CommonTypes::putString($out, $this->postProcessFilter);
		CommonTypes::putString($out, $this->screenshotBorderResourcePath);
		CommonTypes::writeOptional($out, $this->agentCapabilities, fn(ByteBufferWriter $out, EducationSettingsAgentCapabilities $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->codeBuilderOverrideUri, CommonTypes::putString(...));
		CommonTypes::putBool($out, $this->hasQuiz);
		CommonTypes::writeOptional($out, $this->linkSettings, fn(ByteBufferWriter $out, EducationSettingsExternalLinkSettings $v) => $v->write($out));
	}
}
