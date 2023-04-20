<?php
	namespace DigitalSplash\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Base\SingleValue;

	//TODO: Generate SQL String Dynamically
	class Sql extends SingleValue {

		public function __construct() {
			parent::__construct('');
		}

	}
