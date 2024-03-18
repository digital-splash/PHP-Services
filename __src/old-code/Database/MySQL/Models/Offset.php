<?php
	namespace OldCode\DigitalSplash\Database\MySQL\Models;

	use OldCode\DigitalSplash\Database\MySQL\Models\Base\SingleValue;

	class Offset extends SingleValue {

		public function __construct() {
			parent::__construct('OFFSET');
		}

	}
