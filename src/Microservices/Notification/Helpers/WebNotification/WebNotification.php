<?php
	namespace DigitalSplash\Notification\Helpers\WebNotification;

	use DigitalSplash\Notification\Interfaces\INotification;

	class WebNotification implements INotification {

		public function __construct() {}

		public function send(): array {
			return [];
		}
	}
