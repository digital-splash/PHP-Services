<?php
	namespace DigitalSplash\Exceptions\Api;

	use DigitalSplash\Exceptions\Base\BaseException;

	class ApiException extends BaseException {
		protected $message = "Api Exception!";
	}
