<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidEmailException extends BaseException {
		protected $message = 'exception.validation.invalidEmail';
	}
