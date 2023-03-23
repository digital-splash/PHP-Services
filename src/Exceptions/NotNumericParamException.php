<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class NotNumericParamException extends BaseParameterException {
		protected $message = "exception.NotNumericParam";
	}
