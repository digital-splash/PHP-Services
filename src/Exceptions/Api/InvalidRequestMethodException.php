<?php

	namespace DigitalSplash\Exceptions\Api;

	use DigitalSplash\Exceptions\Base\BaseException;
	use DigitalSplash\Models\HttpCode;

	class InvalidRequestMethodException extends BaseException {
		protected $message = 'exception.api.invalidRequestMethod';

		public function __construct(
			int $code = 0,
			int $subCode = 0,
		) {
			parent::__construct($this->message, [], $code, $subCode, HttpCode::NOTALLOWED);
		}
	}
