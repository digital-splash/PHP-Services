<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidPasswordCharactersException extends BaseException {
		protected $message = "exception.InvalidPasswordCharacters";
	}
