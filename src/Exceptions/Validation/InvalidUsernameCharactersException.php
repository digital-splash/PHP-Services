<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidUsernameCharactersException extends BaseException {
		protected $message = 'exception.validation.invalidUsernameCharacters';
	}
