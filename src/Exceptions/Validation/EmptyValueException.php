<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	class EmptyValueException extends BaseParameterException {
		protected $message = 'exception.validation.emptyValue';
	}
