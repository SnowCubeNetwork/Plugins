<?php

namespace pets;

class SheepPet extends Pets {

	const NETWORK_ID = 13;

	const DATA_COLOR_INFO = 16;

    public $width = 1.45;
    public $height = 1.12;


	public function getName() {
		return "SheepPet";
	}

	public function getSpeed() {
		return 1.1;
	}

}
