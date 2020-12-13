<?php

namespace pets;

class WitherPet extends Pets {

	const NETWORK_ID = 52;

	public $width = 3;
	public $height = 1;

	public function getName() {
		return "WitherPet";
	}

	public function getSpeed() {
		return 3;
	}

}
