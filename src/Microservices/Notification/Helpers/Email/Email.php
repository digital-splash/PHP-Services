<?php
	namespace DigitalSplash\Notification\Helpers\Email;

	use DigitalSplash\Notification\Interfaces\IEmail;
	use DigitalSplash\Notification\Interfaces\INotification;

	class Email implements INotification {
		protected IEmail $_email;

		protected $to = [];
		protected $cc = [];
		protected $bcc = [];
		protected $subject = "";
		protected $body = "";
		protected $templateData = [];
		protected $attachments = [];
		protected $mainTemplate = "main.boxed_with_button";
		protected $template = "";

		public function __construct(
			IEmail $email
		) {
			$this->_email = $email;
		}

		public function send(): void {


			$this->_email->send();
		}
	}
