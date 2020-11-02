<?php

namespace hub\task;

use hub\Main;
use hub\scoreboard\Scoreboard;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class ScoreboardTask extends Task {

	private $plugin;

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
	}

	public function onRun($currentTick) {
		foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if (!$player->spawned) {
				return;
			}
			$scoreboard = new Scoreboard($player);
			$scoreboard->setTitle(TextFormat::BOLD . TextFormat::BLUE . $this->plugin->config->get("title"));
			$scoreboard->setLine(1, TextFormat::GOLD . "---------------------");
			$scoreboard->setLine(2, " " . $player->getName());
			$scoreboard->setEmptyLine(3);
			$scoreboard->setLine(4, TextFormat::RED . " Map:");
			$scoreboard->setLine(5, TextFormat::GREEN . " " . $player->getLevel()->getName());
			$scoreboard->setEmptyLine(6);
			$scoreboard->setLine(7, TextFormat::YELLOW . " Players:" . count($this->plugin->getServer()->getOnlinePlayers()))
			$scoreboard->setEmptyLine(8);
			$scoreboard->setLine(9, TextFormat::AQUA . " " . $this->plugin->config->get("server-ip"));
			$scoreboard->setLine(10, TextFormat::GOLD . "---------------------");
		}
	}

}
