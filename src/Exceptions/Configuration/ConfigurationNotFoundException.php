<?php

	namespace DigitalSplash\Exceptions\Configuration;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class ConfigurationNotFoundException extends BaseException {
		protected $message = 'exception.configuration.notFound';
	}
