<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidUsernameCharactersException extends BaseException {
		protected $message = "exception.InvalidUsernameCharacters";
	}
