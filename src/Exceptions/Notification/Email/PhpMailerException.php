<?php

	namespace DigitalSplash\Exceptions\Notification\Email;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	class PhpMailerException extends BaseParameterException {
		protected $message = 'exception.notification.email.phpMailer';
	}
