<?php

/**
 * Created by PhpStorm.
 * User: vp817
 * Date: 1/2/2023
 * Time: 9:32 AM
 */


declare(strict_types=1);

namespace vp817;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\player\Player;
use vp817\player\cache\PlayerCache;
use vp817\player\PlayerManager;

/**
 * Class EventListener
 * @package vp817
 */
class EventListener implements Listener
{

	/** @var Main $main */
	private Main $main;

	public function __construct(Main $main)
	{
		$this->main = $main;
	}

	/**
	 * @return Main
	 */
	public function getMain(): Main
	{
		return $this->main;
	}

	/**
	 * @return PlayerManager
	 */
	public function getPlayerManager(): PlayerManager
	{
		return $this->getMain()->getPlayerManager();
	}

	/**
	 * @param PlayerJoinEvent $event
	 * @return void
	 */
	public function onJoin(PlayerJoinEvent $event): void
	{
		$evPlayer = $event->getPlayer();
		$this->getPlayerManager()->addPlayer($evPlayer);
	}

	/**
	 * @param PlayerQuitEvent $event
	 * @return void
	 */
	public function onQuit(PlayerQuitEvent $event): void
	{
		$evPlayerName = $event->getPlayer()->getName();
		$playerCache = $this->getPlayerManager()->getPlayer($evPlayerName);
		$this->setCompassDirectionToNormal($playerCache, $this->getPlayerDimension($playerCache->getRealPlayer()));
		$this->getPlayerManager()->removePlayer($evPlayerName);
	}

	/**
	 * @param PlayerDeathEvent $event
	 * @return void
	 */
	public function onDeath(PlayerDeathEvent $event): void
	{
		$evPlayer = $event->getPlayer();
		$playerCache = $this->getPlayerManager()->getPlayer($evPlayer->getName());
		$playerCache->setLastDeathPos($event->getPlayer()->getPosition()->asVector3());
	}

	/**
	 * @param PlayerItemHeldEvent $event
	 * @return void
	 */
	public function onItemHeld(PlayerItemHeldEvent $event): void
	{
		$evItem = $event->getItem();
		if ($evItem->getNamedTag()->getTag("isRecoveryCompass") !== null) {
			$playerCache = $this->getPlayerManager()->getPlayer($event->getPlayer()->getName());
			if ($playerCache->getLastDeathPos() !== null) {
				$this->setCompassDirectionToDeathPos($playerCache, $this->getPlayerDimension($playerCache->getRealPlayer()));
			}
		}
	}

	/**
	 * @param PlayerItemUseEvent $event
	 * @return void
	 */
	public function onItemUse(PlayerItemUseEvent $event): void
	{
		$evItem = $event->getItem();
		if ($evItem->getNamedTag()->getTag("isRecoveryCompass") !== null) {
			$playerCache = $this->getPlayerManager()->getPlayer($event->getPlayer()->getName());
			if ($playerCache->getLastDeathPos() !== null) {
				$this->setCompassDirectionToDeathPos($playerCache, $this->getPlayerDimension($playerCache->getRealPlayer()));
			}
		}
	}

	/**
	 * @param PlayerMoveEvent $event
	 * @return void
	 */
	public function onMove(PlayerMoveEvent $event): void
	{
		$playerCache = $this->getPlayerManager()->getPlayer($event->getPlayer()->getName());
		$realPlayer = $playerCache->getRealPlayer();
		if ($playerCache->getLastDeathPos() !== null) {
			$itemInHand = $realPlayer->getInventory()->getItemInHand();
			$lastDeathPos = $playerCache->getLastDeathPos();
			if ($itemInHand->getNamedTag()->getTag("isRecoveryCompass") !== null) {
				if ($realPlayer->getPosition()->distance($lastDeathPos) <= 7) {
					$this->setCompassDirectionToNormal($playerCache, $this->getPlayerDimension($realPlayer));
				}
			}
		}
	}

	/**
	 * @param Player $player
	 * @return int
	 */
	public function getPlayerDimension(Player $player): int
	{
		$generatorName = strtolower($player->getWorld()->getProvider()->getWorldData()->getGenerator());
		if (($generatorName == "vanilla_nether") || ($generatorName == "nether")) {
			return DimensionIds::NETHER;
		} elseif (($generatorName == "ender") || ($generatorName == "end") || ($generatorName == "the_end")) {
			return DimensionIds::THE_END;
		}
		return DimensionIds::OVERWORLD;
	}

	/**
	 * @param PlayerCache $playerCache
	 * @param int $dimensionID
	 * @return void
	 */
	public function setCompassDirectionToNormal(PlayerCache $playerCache, int $dimensionID): void
	{
		$worldSpawnPos = BlockPosition::fromVector3($playerCache->getRealPlayer()->getWorld()->getSpawnLocation());
		$this->setCompassDirection($playerCache->getRealPlayer(), $worldSpawnPos, $worldSpawnPos, $dimensionID);
		$playerCache->setLastDeathPos(null);
	}

	/**
	 * @param PlayerCache $cache
	 * @param int $dimensionID
	 * @return void
	 */
	private function setCompassDirectionToDeathPos(PlayerCache $cache, int $dimensionID): void
	{
		$realPlayer = $cache->getRealPlayer();
		$lastDeathPos = BlockPosition::fromVector3($cache->getLastDeathPos() ?? $realPlayer->getWorld()->getSpawnLocation());
		$this->setCompassDirection($realPlayer, $lastDeathPos, $lastDeathPos, $dimensionID);
	}

	/**
	 * @param Player $player
	 * @param BlockPosition $spawnPosition
	 * @param BlockPosition $causingBlockPosition
	 * @param int $dimensionID
	 * @return void
	 */
	private function setCompassDirection(Player $player, BlockPosition $spawnPosition, BlockPosition $causingBlockPosition, int $dimensionID = DimensionIds::OVERWORLD): void
	{
		$player->getNetworkSession()->sendDataPacket(SetSpawnPositionPacket::playerSpawn($spawnPosition, $dimensionID, $causingBlockPosition));
	}
}
