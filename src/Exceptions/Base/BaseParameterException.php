<?php

	namespace DigitalSplash\Exceptions\Base;

	use DigitalSplash\Microservices\Language\Translate;
	use DigitalSplash\Models\HttpCode;

	class BaseParameterException extends BaseException {
		public function __construct(
			string $param,
			int    $code = 0,
			int    $subCode = 0,
			int    $responseCode = HttpCode::NOTFOUND
		) {
			$this->message = Translate::get($this->message, null, [
				'::params::' => $param,
			]);
			parent::__construct('', [], $code, $subCode, $responseCode);
		}
	}
