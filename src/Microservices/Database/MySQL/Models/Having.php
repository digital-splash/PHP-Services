<?php
	namespace DigitalSplash\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Base\IndexedArray;

	class Having extends IndexedArray {

		public function __construct() {
			parent::__construct(' AND ', 'HAVING');
		}

	}
