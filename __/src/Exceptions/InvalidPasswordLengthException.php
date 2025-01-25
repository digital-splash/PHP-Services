<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidPasswordLengthException extends BaseException {
		protected $message = "exception.InvalidPasswordLength";
	}
