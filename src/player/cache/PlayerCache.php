<?php

/**
 * Created by PhpStorm.
 * User: vp817
 * Date: 1/2/2023
 * Time: 9:43 AM
 */


declare(strict_types=1);

namespace vp817\player\cache;


use pocketmine\math\Vector3;
use pocketmine\player\Player;
use vp817\Main;

/**
 * Class PlayerCache
 * @package vp817\player\cache
 */
class PlayerCache
{

	/** @var Main $main */
	private Main $main;

	/** @var Player $realPlayer */
	private Player $realPlayer;

	/** @var Vector3|null $lastDeathPos */
	private Vector3|null $lastDeathPos;

	/**
	 * @param Main $main
	 * @param Player $realPlayer
	 */
	public function __construct(Main $main, Player $realPlayer)
	{
		$this->main = $main;
		$this->realPlayer = $realPlayer;
	}

	/**
	 * @return Main
	 */
	public function getMain(): Main
	{
		return $this->main;
	}

	/**
	 * @return Player
	 */
	public function getRealPlayer(): Player
	{
		return $this->realPlayer;
	}

	/**
	 * @param Vector3|null $lastDeathPos
	 */
	public function setLastDeathPos(Vector3|null $lastDeathPos): void
	{
		$this->lastDeathPos = $lastDeathPos;
	}

	/**
	 * @return Vector3|null
	 */
	public function getLastDeathPos(): ?Vector3
	{
		return $this->lastDeathPos ?? null;
	}
}
