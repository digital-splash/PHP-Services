<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidPasswordCharactersException extends BaseException {
		protected $message = 'exception.validation.invalidPasswordCharacters';
	}
