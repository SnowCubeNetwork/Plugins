<?php

namespace pets;

class SnowGolemPet extends Pets {

	const NETWORK_ID = 21;

	public $width = 1.281;
	public $height = 1.875;

	public function getName() {
		return "SnowGolemPet";
	}

	public function getSpeed() {
		return 0.25;
	}

}
