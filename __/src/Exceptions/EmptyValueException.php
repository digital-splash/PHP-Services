<?php
	namespace DigitalSplash\Exceptions\Notification;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	class EmptyValueException extends BaseParameterException {
		protected $message = "exception.EmptyValue";
	}
