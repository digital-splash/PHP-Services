<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class NotEmptyParamException extends BaseParameterException {
		protected $message = 'exception.validation.notEmptyParam';
	}
