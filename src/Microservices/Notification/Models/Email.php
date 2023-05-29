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
		private $attachments = [];

		// private $templateData = [];
		// private $mainTemplate = "main.boxed_with_button";
		// private $template = "";

		public function __construct() {
			$this->isProd = false;
			$this->testEmail = 'testing@dgsplash.com';
		}

		public function appendTo(string $name, string $email): void {
			$this->to[] = new Recipient($name, $email);
		}

		/**
		 * @return Recipient[]
		 */
		public function getTo(): array {
			return $this->to;
		}

		public function appendCC(string $name, string $email): void {
			$this->cc[] = new Recipient($name, $email);
		}

		/**
		 * @return Recipient[]
		 */
		public function getCC(): array {
			return $this->cc;
		}

		public function appendBCC(string $name, string $email): void {
			$this->bcc[] = new Recipient($name, $email);
		}
		/**
		 * @return Recipient[]
		 */
		public function getBCC(): array {
			return $this->bcc;
		}

		public function setSubject(string $subject): void {
			$this->subject = $subject;
		}
		public function getSubject(): string {
			return $this->subject;
		}

		public function setBody(string $body): void {
			$this->body = $body;
		}
		public function getBody(): string {
			return $this->body;
		}

		public function setAttachments(array $attachments): void {
			$this->attachments = $attachments;
		}
		public function getAttachments(): array {
			return $this->attachments;
		}
		public function appendToAttachment(string $path, string $name = null): void {
			$this->attachments[] = [
				'path' => $path,
				'name' => $name
			];
		}

		public function setIsProd(bool $isProd): void {
			$this->isProd = $isProd;
		}
		public function getIsProd(): bool {
			return $this->isProd;
		}


		public function setTestEmail(string $testEmail): void {
			$this->testEmail = $testEmail;
		}
		public function getTestEmail(): string {
			return $this->testEmail;
		}
	}
