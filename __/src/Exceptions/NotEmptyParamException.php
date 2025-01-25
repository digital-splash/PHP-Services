<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class NotEmptyParamException extends BaseParameterException {
		protected $message = "exception.NotEmptyParam";
	}
