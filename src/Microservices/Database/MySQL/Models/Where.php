<?php
	namespace DigitalSplash\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Base\IndexedArray;

	class Where extends IndexedArray {

		public function __construct() {
			parent::__construct(' AND ', 'WHERE');
		}

	}
