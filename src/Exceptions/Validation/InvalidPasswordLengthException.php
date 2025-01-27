<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidPasswordLengthException extends BaseException {
		protected $message = 'exception.validation.invalidPasswordLength';
	}
