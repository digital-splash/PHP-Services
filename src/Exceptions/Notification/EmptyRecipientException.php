<?php
	namespace DigitalSplash\Exceptions\Notification;

	use Exception;

	class EmptyRecipientException extends Exception {
		public function __construct() {
			parent::__construct("Recipient is empty");
		}
	}
