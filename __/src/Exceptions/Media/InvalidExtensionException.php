<?php
	namespace DigitalSplash\Exceptions\Media;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class InvalidExtensionException extends BaseParameterException {
		protected $message = "exception.media.InvalidExtension";
	}
