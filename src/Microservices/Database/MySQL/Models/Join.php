<?php
	namespace DigitalSplash\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Base\NonIndexedArray;

	class Join extends NonIndexedArray {

		public function __construct() {
			parent::__construct(' ', '');
		}

	}
