<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidUrlException extends BaseException {
		protected $message = 'exception.validation.invalidUrl';
	}
