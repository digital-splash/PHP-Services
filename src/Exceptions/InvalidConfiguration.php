<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidConfigurationException extends BaseException {
		protected $message = "exception.InvalidConfiguration";
	}
