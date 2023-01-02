<?php

/**
 * Copyright 2018/2022 vp817
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Created by PhpStorm.
 * User: vp817
 * Date: 1/2/2023
 * Time: 9:29 AM
 */


declare(strict_types=1);

namespace vp817\utilities;


use pocketmine\network\mcpe\convert\ItemTranslator;

/**
 * Class Utilities
 * @package vp817\utils
 */
class Utils
{
	public const RECOVERY_COMPASS_ID = 650;

	public static function loadRC()
	{
		$itemTranslator = ItemTranslator::getInstance();
		$ref = new \ReflectionClass($itemTranslator);
		$simpleCoreToNetMappingPR = $ref->getProperty("simpleCoreToNetMapping");
		$simpleCoreToNetMappingPR->setAccessible(true);
		$simpleCoreToNetMapping = $simpleCoreToNetMappingPR->getValue($itemTranslator);
		$simpleNetToCoreMappingPR = $ref->getProperty("simpleNetToCoreMapping");
		$simpleNetToCoreMappingPR->setAccessible(true);
		$simpleNetToCoreMapping = $simpleNetToCoreMappingPR->getValue($itemTranslator);

		$itemsList = json_decode(file_get_contents(\pocketmine\BEDROCK_DATA_PATH . "required_item_list.json"), true);
		$itemMap = json_decode(file_get_contents(\pocketmine\BEDROCK_DATA_PATH . "item_id_map.json"), true);

		$itemMap["minecraft:recovery_compass"] = static::RECOVERY_COMPASS_ID;
		$id = $itemMap["minecraft:recovery_compass"];
		$runtimeID = $itemsList["minecraft:recovery_compass"]["runtime_id"];
		$simpleCoreToNetMapping[$id] = $runtimeID;
		$simpleNetToCoreMapping[$runtimeID] = $id;
		$simpleCoreToNetMappingPR->setValue($itemTranslator, $simpleCoreToNetMapping);
		$simpleNetToCoreMappingPR->setValue($itemTranslator, $simpleNetToCoreMapping);
	}
}