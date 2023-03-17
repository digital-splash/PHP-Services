<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class FileNotFoundException extends BaseParameterException {
		protected $message = "exception.FileNotFound";
	}
