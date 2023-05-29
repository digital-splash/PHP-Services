<?php
	namespace DigitalSplash\Notification\Helpers\Email;

	use DigitalSplash\Notification\Interfaces\INotification;
	use DigitalSplash\Notification\Models\Email\Email as EmailModel;

	class Email implements INotification {
		public EmailModel $model;

		public function __construct() {
			$this->model = new EmailModel();
		}

		public function send(): void {
			$this->sendPhpMailer();
		}

		public function sendPhpMailer(): void {
			$phpMailer = new PhpMailer();
			$phpMailer->model = $this->model;
			$phpMailer->send();
		}
	}
