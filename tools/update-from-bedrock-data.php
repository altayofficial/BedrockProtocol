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

if(count($argv) < 2){
	fwrite(STDERR, "Required args: path to BedrockData" . PHP_EOL);
	exit(1);
}

passthru(PHP_BINARY . " " . __DIR__ . '/generate-protocol-info.php ' . escapeshellarg($argv[1] . '/protocol_info.json'));
passthru(PHP_BINARY . " " . __DIR__ . '/generate-entity-ids.php ' . escapeshellarg($argv[1] . '/entity_id_map.json'));
passthru(PHP_BINARY . " " . __DIR__ . '/generate-level-sound-ids.php ' . escapeshellarg($argv[1] . '/level_sound_id_map.json'));
passthru(PHP_BINARY . " " . __DIR__ . '/generate-command-parameter-types.php ' . escapeshellarg($argv[1] . '/command_arg_types.json'));
