<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class UnknownException extends BaseException {
		protected $message = 'exception.main.unknown';
	}
