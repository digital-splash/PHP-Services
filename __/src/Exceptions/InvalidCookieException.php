<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class InvalidCookieException extends BaseParameterException {
		protected $message = "exception.InvalidCookie";
	}
