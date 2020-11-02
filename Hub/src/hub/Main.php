<?php

namespace hub;

use hub\task\ScoreboardTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase {

	public $config;

	public function onEnable() {
		$this->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($this), 20);
		@mkdir($this->getDataFolder());
		if (!file_exists($this->getDataFolder() . "config.yml")) {
			$this->saveResource("config.yml");
		}
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
			"title" => "SnowCube Network",
			"server-ip" => "play.snowcube.ml"
		]);
	}

}
