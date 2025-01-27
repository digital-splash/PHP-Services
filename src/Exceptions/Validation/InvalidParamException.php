<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class InvalidParamException extends BaseParameterException {
		protected $message = "exception.InvalidParam";
	}
