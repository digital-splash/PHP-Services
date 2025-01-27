<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidUrlException extends BaseException {
		protected $message = "exception.InvalidUrl";
	}
