<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidUsernameLengthException extends BaseException {
		protected $message = "exception.InvalidUsernameLength";
	}
