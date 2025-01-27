<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class NumericParamException extends BaseParameterException {
		protected $message = 'exception.validation.shouldBeNumericParam';
	}
