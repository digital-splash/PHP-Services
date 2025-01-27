<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class RecordAlreadyExistException extends BaseException {
		protected $message = "exception.AlreadyExist";
	}
