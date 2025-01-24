<?php

	namespace DigitalSplash\Exceptions\Api;

	use DigitalSplash\Exceptions\Base\BaseException;
	use DigitalSplash\Models\HttpCode;

	class InvalidRequestMethodException extends BaseException {
		protected $message = "Invalid Request Method";

		public function __construct(
			int $code = 0,
			int $subcode = 0,
		) {
			parent::__construct($this->message, [], $code, $subcode, HttpCode::NOTALLOWED);
		}
	}
