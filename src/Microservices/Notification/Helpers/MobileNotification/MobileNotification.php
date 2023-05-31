<?php
	namespace DigitalSplash\Notification\Helpers\MobileNotification;

	use DigitalSplash\Notification\Interfaces\INotification;

	class MobileNotification implements INotification {

		public function __construct() {}

		public function send(): array {
			return [];
		}
	}
