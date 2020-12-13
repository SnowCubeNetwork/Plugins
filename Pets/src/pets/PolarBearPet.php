<?php

namespace pets;

class PolarBearPet extends Pets {

	const NETWORK_ID = 28;

	public $width = 1.3;
	public $height = 1.4;

	public function getName() {
		return "PolarBearPet";
	}

	public function getSpeed() {
		return 3;
	}

}
