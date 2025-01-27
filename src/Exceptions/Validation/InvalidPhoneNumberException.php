<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidPhoneNumberException extends BaseException {
		protected $message = "exception.InvalidPhoneNumber";
	}
