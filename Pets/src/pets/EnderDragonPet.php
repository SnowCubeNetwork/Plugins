<?php

namespace pets;

class EnderDragonPet extends Pets {

	const NETWORK_ID = 53;

	public $width = 16;
	public $height = 8;
	public $length = 16;

	public function getName() {
		return "EnderDragonPet";
	}

	public function getSpeed() {
		return 3;
	}

}
