<?php

namespace hub\scoreboard;

use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\Player;

class Scoreboard {

	private $player;
	protected $scoreboardLines = [];

	public function __construct(Player $player) {
		$this->player = $player;
	}

	public function setTitle($title) {
		$pk = new SetDisplayObjectivePacket();
		$pk->displaySlot = "sidebar";
		$pk->objectiveName = $this->player->getName();
		$pk->displayName = $title;
		$pk->criteriaName = "dummy";
		$pk->sortOrder = 0;
		$this->player->sendDataPacket($pk);
	}

	public function getLine($score) {
		return empty($this->scoreboardLines[$score]) ? null : $this->scoreboardLines[$score];
	}

	public function clear() {
		for ($line = 0; $line <= 15; $line++) {
			$this->removeLine($line);
		}
	}

	public function removeLine($line) {
		$pk = new SetScorePacket();
		$pk->type = SetScorePacket::TYPE_REMOVE;
		$entry = new ScorePacketEntry();
		$entry->objectiveName = $this->player->getName();
		$entry->score = 15 - $line;
		$entry->scoreboardId = ($line);
		$pk->entries[] = $entry;
		$this->player->sendDataPacket($pk);
	}

	public function setLine($score, $line) {
		$entry = new ScorePacketEntry();
		$entry->objectiveName = $this->player->getName();
		$entry->type = $entry::TYPE_FAKE_PLAYER;
		$entry->customName = $line;
		$entry->score = $score;
		$entry->scoreboardId = $score;
		if (isset($this->scoreboardLines[$score])) {
			$pk = new SetScorePacket();
			$pk->type = $pk::TYPE_REMOVE;
			$pk->entries[] = $entry;
			$this->player->sendDataPacket($pk);
		}
		$pk = new SetScorePacket();
		$pk->type = $pk::TYPE_CHANGE;
		$pk->entries[] = $entry;
		$this->player->sendDataPacket($pk);
		$this->scoreboardLines[$score] = $line;
	}

	public function setEmptyLine($line) {
		$text = str_repeat(" ", $line);
		$this->setLine($line, $text);
	}

}
