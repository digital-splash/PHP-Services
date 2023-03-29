<?php
	namespace DigitalSplash\Exceptions\Base;

	use Exception;
	use DigitalSplash\Language\Helpers\Translate;

	class BaseParameterException extends Exception {

		public function __construct(
			string $param
		) {
			$this->message = Translate::TranslateString($this->message, null, [
				"::params::" => $param
			]);
			parent::__construct();
		}
	}
