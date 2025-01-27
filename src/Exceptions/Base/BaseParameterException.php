<?php

	namespace DigitalSplash\Exceptions\Base;

	use DigitalSplash\Language\Helpers\Translate;
	use DigitalSplash\Models\HttpCode;

	class BaseParameterException extends BaseException {
		public function __construct(
			string $param,
			int    $code = 0,
			int    $subcode = 0,
			int    $responseCode = HttpCode::NOTFOUND
		) {
			$this->message = Translate::TranslateString($this->message, null, [
				"::params::" => $param,
			]);
			parent::__construct('', [], $code, $subcode, $responseCode);
		}
	}
