<?php

	namespace DigitalSplash\Exceptions\Base;

	use DigitalSplash\Helpers\Helper;

	use DigitalSplash\Microservices\Language\Translate;
	use DigitalSplash\Models\HttpCode;
	use Exception;

	class BaseException extends Exception {
		protected int $responseCode;
		protected int $subCode;

		public function __construct(
			string $message = '',
			array  $replace = [],
			int    $code = 0,
			int    $subCode = 0,
			int    $responseCode = HttpCode::NOTFOUND
		) {
			if (!Helper::isNullOrEmpty($message)) {
				$this->message = $message;
			}
			$this->responseCode = $responseCode;
			$this->code = $code;
			$this->subCode = $subCode;

			$this->message = Translate::get($this->message, null, $replace);
			// http_response_code($this->responseCode);

			parent::__construct();
		}

		public function getResponseCode(): int {
			return $this->responseCode;
		}

		public function getSubCode(): int {
			return $this->subCode;
		}
	}
