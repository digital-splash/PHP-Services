<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidPhoneNumberException extends BaseException {
		protected $message = 'exception.validation.invalidPhoneNumber';
	}
