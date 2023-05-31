<?php
	namespace DigitalSplash\Notification\Helpers\SMS;

	use DigitalSplash\Notification\Interfaces\INotification;

	class SMS implements INotification {

		public function __construct() {}

		public function send(): array {
			return [];
		}
	}
