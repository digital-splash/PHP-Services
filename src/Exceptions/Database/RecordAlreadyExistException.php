<?php

	namespace DigitalSplash\Exceptions\Database;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class RecordAlreadyExistException extends BaseException {
		protected $message = 'exception.database.recordAlreadyExist';
	}
