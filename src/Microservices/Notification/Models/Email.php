<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Exceptions\Notification\EmptyRecipientException;
	use DigitalSplash\Exceptions\Notification\PhpMailerException;
	use DigitalSplash\Notification\Models\Recipient;

	class Email {

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

		/**
		 * @var Recipient[]
		 */
		private array $replyTo;


		private string $subject;
		private string $body;
		private $attachments = [];

		// private $templateData = [];
		// private $mainTemplate = "main.boxed_with_button";
		// private $template = "";

		public function __construct() {}

		public function appendTo(string $name, string $email): void {
			$this->to[] = new Recipient($name, $email);
		}

		/**
		 * @return Recipient[]
		 */
		public function getTo(): array {
			return $this->to;
		}

		public function clearTo(): void {
			$this->to = [];
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

		public function clearCC(): void {
			$this->cc = [];
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

		public function clearBCC(): void {
			$this->bcc = [];
		}

		public function appendReplyTo(string $name, string $email): void {
			$this->replyTo[] = new Recipient($name, $email);
		}

		/**
		 * @return Recipient[]
		 */
		public function getReplyTo(): array {
			return $this->replyTo;
		}

		public function clearReplyTo(): void {
			$this->replyTo = [];
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

		public function fixForNonProduction(): void {
			if (!EmailConfiguration::getIsProd()) {
				$replaced = [];

				if (!empty($this->getTo())) {
					$to = '';
					foreach ($this->getTo() as $toRes) {
						$to .= $toRes->getEmail() . ';';
					}
					$replaced[] = "TO:" . $to;
					$this->clearTo();
					$this->appendTo('Test', 'testing@dgsplash.com');
				}

				if (!empty($this->getCc())) {
					$cc = '';
					foreach ($this->getCc() as $ccRes) {
						$cc .= $ccRes->getEmail() . ';';
					}
					$replaced[] = "CC:" . $cc;
					$this->clearCC();
				}

				if (!empty($this->getBcc())) {
					$bcc = '';
					foreach ($this->getBcc() as $bccRes) {
						$bcc .= $bccRes->getEmail() . ';';
					}
					$replaced[] = "BCC:" . $bcc;
					$this->clearBCC();
				}

				if (!empty($replaced)) {
					$this->subject .= " [Replaced " . implode(" - ", $replaced) . "]";
				}
			}
		}

		public function validateBeforeSend(): void {
			if (empty($this->getTo())) {
				throw new EmptyRecipientException();
			}

			if (empty($this->getSubject())) {
				throw new PhpMailerException("Subject is empty");
			}
		}

	}