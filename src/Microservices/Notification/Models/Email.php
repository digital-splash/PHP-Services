<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Exceptions\Notification\EmptyValueException;
	use DigitalSplash\Models\Tenant;

	class Email {
		private string $subject;
		private string $body;
		private array $attachments;

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
			echo $body;
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
			if (!Tenant::isProd()) {
				if (!empty($replaced)) {
					$this->subject .= " [Replaced " . implode(" - ", $replaced) . "]";
				}
			}
		}

		public function validate(): void {
			if (empty($this->getSubject())) {
				throw new EmptyValueException("Subject");
			}

			if (empty($this->getBody())) {
				throw new EmptyValueException("Body");
			}
		}

	}
