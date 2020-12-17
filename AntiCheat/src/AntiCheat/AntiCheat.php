<?php

namespace AntiCheat;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use AntiCheat\task\CheckPlayerTask;

class AntiCheat extends PluginBase implements Listener {

	public $allowedPlayers = [];
	protected $spamPlayers = [];

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getScheduler()->scheduleRepeatingTask(new CheckPlayerTask($this), 20);
	}

	public function onToggleFlight(PlayerToggleFlightEvent $event) {
		$player = $event->getPlayer();
		if (!$player->isOp()) {
			if ($event->isFlying()) {
				$event->setCancelled(true);
			}
		}
	}

	public function onRecieve(DataPacketReceiveEvent) {
		$packet = $event->getPacket();
		if ($packet instanceof LoginPacket) {
			if ($packet->clientId === 0) {
				$event->setCancelled(true); // prevent ToolBox
			}
		}
	}

	public function onCommandPreprocess(PlayerCommandPreprocessEvent $event) {
		$player = $event->getPlayer();
		$cooldown = microtime(true);
		if (isset($this->spamPlayers[$player->getName()])) {
			if (($cooldown - $this->spamPlayers[$player->getName()]['cooldown']) < 2) {
				$player->sendMessage(TextFormat::RED . "Please wait a few moments before sending another message");
				$event->setCancelled(true);
			}
		}
		$this->spamPlayers[$player->getName()]['cooldown'] = $cooldown;
	}

	public function onDamage(EntityDamageEvent $event) {
		$entity = $event->getEntity();
		if ($event instanceof EntityDamageByEntityEvent && $entity instanceof Player) {
			$damager = $event->getDamager();
			if ($damager instanceof Player) {
				if ($damager->getGamemode === Player::CREATIVE || $damager->getInventory()->getItemInHand()->getId() === Item::BOW) {
					return;
				}
				if ($damager->distance($entity) > 3.9) {
					$event->setCancelled(true);
				}
			}
		}
	}

	public function onKick(PlayerKickEvent $event) {
		$reason = $event->getReason();
		if ($reason == $this->getServer()->getLanguage()->translateString("kick.reason.cheat"), ["%ability.noclip"]) {
			$pk = new AdventureSettingsPacket();
			$pk->setFlag(AdventureSettingsPacket::NO_CLIP, false);
			$event->setCancelled(true);
		}
	}

}
