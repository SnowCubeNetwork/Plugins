<?php

namespace pets;

class VillagerPet extends Pets {

	const NETWORK_ID = 15;

	public $width = 0.6;
	public $height = 1.9;

	public function getName() {
		return "VillagerPet";
	}

	public function getSpeed() {
		return 4.317;
	}

}
