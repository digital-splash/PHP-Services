<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidUsernameLengthException extends BaseException {
		protected $message = 'exception.validation.invalidUsernameLength';
	}
