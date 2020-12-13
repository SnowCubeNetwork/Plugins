<?php

namespace pets;

class IronGolemPet extends Pets {

	const NETWORK_ID = 20;

	public $width = 1.4;
	public $height = 2.9;

	public function getName() {
		return "IronGolemPet";
	}

	public function getSpeed() {
		return 0.25;
	}

}
