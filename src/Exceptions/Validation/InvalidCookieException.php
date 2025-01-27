<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class InvalidCookieException extends BaseParameterException {
		protected $message = 'exception.validation.invalidCookie';
	}
