<?php
	namespace DigitalSplash\Exceptions\Base;

	use DigitalSplash\Language\Helpers\Translate;
	use DigitalSplash\Models\HttpCode;

	class BaseParameterException extends BaseException {

		public function __construct(
			string $param
		) {
			$this->message = Translate::TranslateString($this->message, null, [
				"::params::" => $param
			]);
			parent::__construct('', [], HttpCode::UNPROCESSABLE);
		}
	}
