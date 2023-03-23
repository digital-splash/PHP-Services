<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidEmailException extends BaseException {
		protected $message = "exception.InvalidEmail";
	}
