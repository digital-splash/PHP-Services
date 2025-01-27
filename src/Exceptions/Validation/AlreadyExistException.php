<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	class AlreadyExistException extends BaseParameterException {
		protected $message = 'exception.validation.parameterAlreadyExists';
	}
