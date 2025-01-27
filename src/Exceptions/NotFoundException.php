<?php

	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;

	final class NotFoundException extends BaseException {
		protected $message = 'exception.main.notFound';
	}
