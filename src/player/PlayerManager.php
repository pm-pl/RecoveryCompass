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
 * Time: 9:34 AM
 */


declare(strict_types=1);

namespace vp817\player;


use pocketmine\player\Player;
use vp817\Main;
use vp817\player\cache\PlayerCache;

/**
 * Class PlayerManager
 * @package vp817\player
 */
class PlayerManager
{
	/** @var Main $main */
	private Main $main;

	/** @var PlayerCache[] $list */
	private array $list = [];

	public function __construct(Main $main)
	{
		$this->main = $main;
	}

	/**
	 * @return Main
	 */
	private function getMain(): Main
	{
		return $this->main;
	}

	/**
	 * @param Player $player
	 * @return void
	 */
	public function addPlayer(Player $player): void
	{
		$this->list[$player->getName()] = new PlayerCache($this->getMain(), $player);
	}

	/**
	 * @param string $name
	 * @return PlayerCache|null
	 */
	public function getPlayer(string $name): ?PlayerCache
	{
		if ($this->list[$name] instanceof PlayerCache) {
			return $this->list[$name];
		}
		return null;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function removePlayer(string $name): void
	{
		unset($this->list[$name]);
	}

	/**
	 * @return PlayerCache[]
	 */
	public function getPlayers(): array
	{
		return $this->list;
	}

	/**
	 * @return void
	 */
	public function removePlayers(): void
	{
		foreach ($this->list as $player) {
			unset($this->list[$player->getRealPlayer()->getName()]);
		}
	}
}