<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Exceptions\Notification\EmptyValueException;

	class Email {
		private string $subject;
		private string $body;
		private array $attachments;

		// private $templateData = [];
		// private $mainTemplate = "main.boxed_with_button";
		// private $template = "";

		public function __construct() {
			$this->subject = '';
			$this->body = '';
			$this->attachments = [];
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

		public function fixForNonProduction(array $replaced): void {
			if (!EmailConfiguration::getIsProd()) {
				if (!empty($replaced)) {
					$this->subject .= " [Replaced " . implode(" - ", $replaced) . "]";
				}
			}
		}

		public function validate(): void {
			if (empty($this->getSubject())) {
				throw new EmptyValueException("Subject");
			}
		}

	}
