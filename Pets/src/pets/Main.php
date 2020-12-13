<?php

namespace pets;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pets\PetCommand;

class Main extends PluginBase implements Listener {

	public static $pet;
	public static $petState;
	public static $isPetChanging;
	public static $type;
	public $petType;
	public $wishPet;

	public function onEnable() {
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . "players");
		$server = Server::getInstance();
		Entity::registerEntity(BatPet::class);
		Entity::registerEntity(BlazePet::class);
		Entity::registerEntity(BlockPet::class);
		Entity::registerEntity(BrownRabbitPet::class);
		Entity::registerEntity(ChargedCreeperPet::class);
		Entity::registerEntity(ChickenPet::class);
		Entity::registerEntity(CreeperPet::class);
		Entity::registerEntity(EnderDragonPet::class);
		Entity::registerEntity(EnderPet::class);
		Entity::registerEntity(IronGolemPet::class);
		Entity::registerEntity(MagmaPet::class);
		Entity::registerEntity(OcelotPet::class);
		Entity::registerEntity(PigPet::class);
		Entity::registerEntity(PolarBearPet::class);
		Entity::registerEntity(RabbitPet::class);
		Entity::registerEntity(SheepPet::class);
		Entity::registerEntity(SilverfishPet::class);
		Entity::registerEntity(SnowGolemPet::class);
		Entity::registerEntity(VillagerPet::class);
		Entity::registerEntity(WhiteRabbitPet::class);
		Entity::registerEntity(WitherPet::class);
		Entity::registerEntity(WolfPet::class);
		Entity::registerEntity(ZombiePet::class);
		Entity::registerEntity(ZombieVillagerPet::class);
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
		$subcommand = strtolower(array_shift($args));
		switch ($subcommand) {
			case "give":
				if (count($args) < 1) {
					array_unshift($args, $sender->getDisplayName());
				}
				if (!$this->getConfig()->get('onlyOp') || $sender->hasPermission("pets")) {
					if ($this->givePet(...$args)) {
                        $sender->sendMessage(TextFormat::BLUE . '[Pets] Unable to give ' . $args[0] . 'a new pet!');
                    }
                    return true;
				}
				$sender->sendMessage(TextFormat::RED . "[Pets] You don't have permission to do that...");
				return true;
			case "remove":
				if (count($args) < 1) {
					array_shift($args, $sender->getDisplayName());
				}
				if (!$this->getConfig->get('onlyOp') || $sender->hasPermission("pets")) {
					$args[] = true;
					if ($this->removePet(...$args)) {
						$sender->sendMessage(TextFormat::BLUE . "[Pets] " . $args[0] . "'s pet was removed!")
					} else {
						$sender->sendMessage(TextFormat::RED . "[Pets] Unable to remove " . $args[0] . "'s pet!");
					}
					return true;
				}
				$sender->sendMessage(TextFormat::RED . "[Pets] You don't have permission to do that...");
				return true;
			case "find":
				if (count($args) < 1) {
					array_shift($args, $sender->getDisplayName());
				}
				if (!$this->getConfig()->get('onlyOp') || $sender->hasPermission("pets")) {
					if ($this->findPet(...$args)) {
						$sender->sendMessage(TextFormat::BLUE . "[Pets] " . $args[0] . "'s pet was found!");
					} else {
						$sender->sendMessage(TextFormat::RED . "[Pets] Unable to find " . $args[0] . "'s pet!");
					}
					return true;
				}
				$sender->sendMessage(TextFormat::RED . "[Pets] You don't have permission to do that...");
				return true;
			case "help":
				$sender->sendMessage(TextFormat::GREEN . "[Pets] Available commands: give, remove, find");
				return true;
			default:
				return true;
		}
	}

	public function create($player,$type, Position $source, ...$args) {
		$chunk = $source->getLevel()->getChunk($source->x >> 4, $source->z >> 4, true);
		$nbt = new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", $source->x),
				new DoubleTag("", $source->y),
				new DoubleTag("", $source->z)
			]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
			]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", $source instanceof Location ? $source->yaw : 0),
				new FloatTag("", $source instanceof Location ? $source->pitch : 0)
			]),
		]);
		$pet = Entity::createEntity($type, $chunk, $nbt, ...$args);
		$data = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
		$data->set("type", $type); 
		$data->save();
		$pet->setOwner($player);
		$pet->spawnToAll();
		return $pet; 
	}

	public function createPet(Player $player, $type, $holdType = "") {
		if (isset($this->pet[$player->getName()]) != true) {	
			$len = rand(8, 12); 
			$x = (-sin(deg2rad($player->yaw))) * $len  + $player->getX();
			$z = cos(deg2rad($player->yaw)) * $len  + $player->getZ();
			$y = $player->getLevel()->getHighestBlockAt($x, $z);
			$source = new Position($x , $y + 2, $z, $player->getLevel());
			if (isset(self::$type[$player->getName()])){
				$type = self::$type[$player->getName()];
			}
			switch ($type) {
				case "BatPet":
					break;
				case "BlazePet":
					break;
				case "BlockPet":
					break;
				case "BrownRabbitPet":
					break;
				case "ChargedCreeperPet":
					break;
				case "ChickenPet":
					break;
				case "CreeperPet":
					break;
				case "EnderDragonPet":
					break;
				case "EnderPet":
					break;
				case "IronGolemPet":
					break;
				case "MagmaPet":
					break;
				case "OcelotPet":
					break;
				case "PigPet":
					break;
				case "PolarBearPet":
					break;
				case "RabbitPet":
					break;
				case "SheepPet":
					break;
				case "SilverfishPet":
					break;
				case "SnowGolemPet":
					break;
				case "VillagerPet":
					break;
				case "WhiteRabbitPet":
					break;
				case "WitherPet":
					break;
				case "WolfPet":
					break;
				case "ZombiePet":
					break;
				case "ZombieVillagerPet":
					break;
 				default:
 					$pets = [
						"BatPet",
						"BlazePet",
						"BlockPet",
						"BrownRabbitPet",
						"ChargedCreeperPet",
						"ChickenPet",
						"CreeperPet",
						"EnderDragonPet",
						"EnderPet",
						"IronGolemPet",
						"MagmaPet",
						"OcelotPet",
						"PigPet",
						"PolarBearPet",
						"RabbitPet",
						"SheepPet",
						"SilverfishPet",
						"SnowGolemPet",
						"VillagerPet",
						"WhiteRabbitPet",
						"WitherPet",
						"WolfPet",
						"ZombiePet",
						"ZombieVillagerPet",
					];
 					$type = $pets[rand(0, 5)];
 			}
			$pet = $this->create($player,$type, $source);
			return $pet;
 		}
	}

	public function onLoginLogin(PlayerLoginEvent $event) {
		if (isset($this->players[$event->getPlayer()->getDisplayName()])) {
			$this->givePet($event->getPlayer()->getDisplayName(), $this->players[$event->getPlayer()->getDisplayName()]);
		}
	}

	public function onPlayerQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$this->disablePet($player);
	}

	public function onPlayerRespawn(PlayerRespawnEvent $event) {
		if (isset($this->players[$event->getPlayer()->getDisplayName()])) {
			$this->givePet($event->getPlayer()->getDisplayName(), $this->players[$event->getPlayer()->getDisplayName()]);
		}
	}

	public function disablePet(Player $player) {
		if (isset(self::$pet[$player->getName()])) {
			self::$pet[$player->getName()]->close();
			self::$pet[$player->getName()] = null;
		}
	}

	public function changePet(Player $player, $newtype) {
		$type = $newtype;
		$this->disablePet($player);
		self::$pet[$player->getName()] = $this->createPet($player, $newtype);
	}

	public function getPet($player) {
		return self::$pet[$player];
	}

	public function givePet($user = "", $pet = "") {
		if (($player = $this->getServer()->getPlayerExact($user)) instanceof Player) {
			if (!isset($this->pets[$player->getDisplayName()])) {
				$this->pets[$player->getDisplayName()] = self::createPet($player, $pet);
				$this->pets[$player->getDisplayName()]->returnToOwner();
				$this->players[$player->getDisplayName()] = $this->pets[$player->getDisplayName()]->getName();
				return true;
			}
		}
		return false;
	}

	public function removePet($user = "", $unset = false) {
		if (($player = $this->getServer()->getPlayerExact($user)) instanceof Player) {
			if (isset($this->pets[$player->getDisplayName()])) {
				$this->pets[$player->getDisplayName]->fastClose();
				unset($this->pets[$player->getDisplayName()]);
				if ($unset) {
					unset($this->players[$player->getDisplayName()]);
				}
				return true;
			}
		}
		return false;
	}

	public function findPet($user = "") {
		if (($player = $this->getServer()->getPlayerExact($user)) instanceof Player) {
			if (isset($this->pets[$player->getDisplayName()])) {
				$this->pets[$player->getDisplayName()]->returnToOwner();
				return true;
			}
		}
		return false;
	}

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$data = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
		if ($data->exists("type")) { 
			$type = $data->get("type");
			$this->changePet($player, $type);
		}
		if ($data->exists("name")) { 
			$name = $data->get("name");
			$this->getPet($player->getName())->setNameTag($name);
		}
	}

}
