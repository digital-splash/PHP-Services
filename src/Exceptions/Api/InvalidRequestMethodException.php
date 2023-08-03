<?php
	namespace DigitalSplash\Exceptions\Api;

	use DigitalSplash\Exceptions\Base\BaseException;
	use DigitalSplash\Models\HttpCode;

	class InvalidRequestMethodException extends BaseException {
		protected $message = "Invalid Request Method";

		public function __construct() {
			parent::__construct($this->message, [], HttpCode::NOTALLOWED);
		}
	}
