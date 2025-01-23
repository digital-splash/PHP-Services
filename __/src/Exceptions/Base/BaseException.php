<?php

	namespace DigitalSplash\Exceptions\Base;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Language\Helpers\Translate;
	use DigitalSplash\Models\HttpCode;
	use Exception;

	class BaseException extends Exception {
		protected int $responseCode;
		protected int $subcode;

		public function __construct(
			string $message = "",
			array  $replace = [],
			int    $code = 0,
			int    $subcode = 0,
			int    $responseCode = HttpCode::NOTFOUND
		) {
			if (!Helper::IsNullOrEmpty($message)) {
				$this->message = $message;
			}
			$this->responseCode = $responseCode;
			$this->code = $code;
			$this->subcode = $subcode;

			$this->message = Translate::TranslateString($this->message, null, $replace);
			// http_response_code($this->responseCode);

			parent::__construct();
		}

		public function getResponseCode(): int {
			return $this->responseCode;
		}

		public function getSubcode(): int {
			return $this->subcode;
		}
	}
