<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Notification\Models\Recipient;

	class Email {
		private bool $isProd;
		private string $testEmail;

		/**
		 * @var Recipient[]
		 */
		private array $to;

		/**
		 * @var Recipient[]
		 */
		private array $cc;

		/**
		 * @var Recipient[]
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
			$this->to[] = new Recipient($name, $email);
		}

		/**
		 * @return Recipient[]
		 */
		public function getTo(): array {
			return $this->to;
		}

	}
