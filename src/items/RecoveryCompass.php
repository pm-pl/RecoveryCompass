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
 * Time: 10:12 AM
 */


declare(strict_types=1);

namespace vp817\items;


use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

/**
 * Class RecoveryCompass
 * @package vp817\items
 */
class RecoveryCompass extends Item
{

	/**
	 * @param ItemIdentifier $identifier
	 * @param string $name
	 */
	public function __construct(ItemIdentifier $identifier, string $name = "Unknown")
	{
		parent::__construct($identifier, $name);
		$this->getNamedTag()->setByte("isRecoveryCompass", 1);
	}
}