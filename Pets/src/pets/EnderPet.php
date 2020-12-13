<?php

namespace pets;

class EnderPet extends Pets {

	const NETWORK_ID = 38;

	public $width = 0.3;
	public $length = 0.9;
	public $height = 1.8;

	public function getName() {
		return "EnderPet";
	}

	public function getSpeed() {
		return 1.2;
	}

}
