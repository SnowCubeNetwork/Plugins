<?php

namespace pets;

class ZombieVillagerPet extends Pets {

	const NETWORK_ID = 44;

	public $width = 1.031;
	public $length = 0.891;
	public $height = 2.125;

	public function getName() {
		return "ZombieVillagerPet";
	}

	public function getSpeed() {
		return 0.2;
	}

}
