<?php
	namespace DigitalSplash\Exceptions\Base;

	use Exception;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Language\Helpers\Translate;
	use DigitalSplash\Models\HttpCode;

	class BaseException extends Exception {

		public function __construct(
			string $message = "",
			array $replace = [],
			int $responseCode = HttpCode::INTERNALERROR
		) {
			if (!Helper::IsNullOrEmpty($message)) {
				$this->message = $message;
			}
			http_response_code($responseCode);
			$this->message = Translate::TranslateString($this->message, null, $replace);
			parent::__construct();
		}
	}
