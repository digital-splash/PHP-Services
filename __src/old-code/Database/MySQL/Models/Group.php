<?php
	namespace OldCode\DigitalSplash\Database\MySQL\Models;

	use OldCode\DigitalSplash\Database\MySQL\Models\Base\NonIndexedArray;

	class Group extends NonIndexedArray {

		public function __construct() {
			parent::__construct(', ', 'GROUP BY');
		}

	}
