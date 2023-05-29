<?php
	namespace DigitalSplash\Notification\Models\Email;

	class Email {
		private bool $isProd;
		private string $testEmail;

		/**
		 * @var Recepient[]
		 */
		private array $to;

		/**
		 * @var Recepient[]
		 */
		private array $cc;

		/**
		 * @var Recepient[]
		 */
		private array $bcc;


		private string $subject;
		private string $body;

		// private $templateData = [];
		// private $attachments = [];
		// private $mainTemplate = "main.boxed_with_button";
		// private $template = "";

		public function __construct() {
			$this->isProd = false;
			$this->testEmail = 'testing@dgsplash.com';
		}

		public function appendTo(string $name, string $email) {
			$this->to[] = new Recepient($name, $email);
		}

		/**
		 * @return Recepient[]
		 */
		public function getTo(): array {
			return $this->to;
		}

	}
