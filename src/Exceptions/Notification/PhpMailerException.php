<?php
	namespace DigitalSplash\Exceptions\Notification;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	class PhpMailerException extends BaseParameterException {
		protected $message = "exception.notification.PhpMailer";
	}
