<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class InvalidParamException extends BaseParameterException {
		protected $message = 'exception.validation.invalidParam';
	}
