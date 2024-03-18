<?php
	namespace OldCode\DigitalSplash\Database\MySQL\Models;

	use OldCode\DigitalSplash\Database\MySQL\Models\Base\IndexedArray;

	class Where extends IndexedArray {

		public function __construct() {
			parent::__construct(' AND ', 'WHERE');
		}

	}
