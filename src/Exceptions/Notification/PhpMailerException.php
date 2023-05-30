<?php
	namespace DigitalSplash\Exceptions\Notification;
	use Exception;

	class PhpMailerException extends Exception {
		public function __construct($message) {
			parent::__construct($message);
		}
	}