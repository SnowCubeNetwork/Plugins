<?php

namespace AntiCheat\task;

use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\scheduler\Task;
use AntiCheat\AntiCheat as Main;

class CheckPlayerTask extends Task {

	private $plugin;

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
	}

	public function onRun(int $currentTick) {
		foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if ($player->isOp()) {
				return;
			}
			if (!$player->isFlying()) {
				return;
			}
			$player->setFlying(false);
		}
	}

}
