<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class ConfigurationNotFoundException extends BaseException {
		protected $message = "exception.ConfigurationNotFound";
	}
