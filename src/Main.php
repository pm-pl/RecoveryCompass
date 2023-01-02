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
 * Time: 9:22 AM
 */


declare(strict_types=1);

namespace vp817;


use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemIdentifier;
use pocketmine\plugin\PluginBase;
use vp817\items\RecoveryCompass;
use vp817\player\PlayerManager;
use vp817\utilities\Utils;

/**
 * Class Loader
 * @package vp817
 */
class Main extends PluginBase
{

	/** @var PlayerManager $playerManager */
	private PlayerManager $playerManager;

	protected function onEnable(): void
	{
		Utils::loadRC();

		$this->playerManager = new PlayerManager($this);

		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

		// TODO: recipe
		// the required items is not in pm

		CreativeInventory::getInstance()->add(new RecoveryCompass(new ItemIdentifier(Utils::RECOVERY_COMPASS_ID, 0), "Recovery Compass"));
	}

	/**
	 * @return PlayerManager
	 */
	public function getPlayerManager(): PlayerManager
	{
		return $this->playerManager;
	}
}