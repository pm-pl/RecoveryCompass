<?php

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
