<?php

namespace pets;

class CreeperPet extends Pets {

	const NETWORK_ID = 33;

	public $width = 0.6;
	public $length = 0.6;
	public $height = 1.8;

	public function getName() {
		return "CreeperPet";
	}

	public function getSpeed() {
		return 1.2;
	}

}
