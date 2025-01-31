<?php

	use DigitalSplash\Core\Env;
	use DigitalSplash\Microservices\Language\Language;
	use DigitalSplash\Microservices\Language\Translate;

	Env::init();

	if (!PHPUNIT_TEST_SUITE) {
		Language::init();
		Translate::init();
	}
