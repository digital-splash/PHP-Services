<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	class AlreadyExistException extends BaseParameterException {
		protected $message = "::params:: Already Exist!";
	}
