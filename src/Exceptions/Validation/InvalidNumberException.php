<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidNumberException extends BaseException {
		protected $message = 'exception.validation.invalidNumber';
	}
