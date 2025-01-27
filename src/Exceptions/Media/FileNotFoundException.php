<?php

	namespace DigitalSplash\Exceptions\Media;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class FileNotFoundException extends BaseParameterException {
		protected $message = 'exception.media.fileNotFound';
	}
