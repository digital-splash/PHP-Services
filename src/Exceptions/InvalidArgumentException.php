<?php
	namespace DigitalSplash\Exceptions;

	use Exception;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Language\Helpers\Translate;

	final class InvalidArgumentException extends Exception {
		protected $message = "exception.InvalidArgument";

		public function __construct(
			string $argument,
			string $value,
			?string $allowed=null
		) {
			if (!Helper::IsNullOrEmpty($allowed)) {
				$this->message = "exception.InvalidArgumentWithAllowed";
			}

			$this->message = Translate::TranslateString($this->message, null, [
				"::argument::" => $argument,
				"::value::" => $value,
				"::allowed::" => $allowed,
			]);
			parent::__construct();
		}
	}
