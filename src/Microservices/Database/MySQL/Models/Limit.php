<?php
	namespace DigitalSplash\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Base\SingleValue;

	class Limit extends SingleValue {

		public function __construct() {
			parent::__construct('LIMIT');
		}

	}
