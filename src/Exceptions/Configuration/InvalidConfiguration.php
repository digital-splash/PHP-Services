<?php
	namespace DigitalSplash\Exceptions\Configuration;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class InvalidConfigurationException extends BaseException {
		protected $message = "exception.Configuration.Invalid";
	}
