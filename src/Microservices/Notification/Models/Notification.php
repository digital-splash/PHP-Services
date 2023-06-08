<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Exceptions\Notification\EmptyValueException;
use DigitalSplash\Models\Tenant;
use DigitalSplash\Notification\Models\Recipient;

	class Notification {

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

		public Email $email;


		public function __construct() {
			$this->to = [];
			$this->cc = [];
			$this->bcc = [];
			$this->replyTo = [];

			$this->email = new Email();
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

		public function setAttachments(array $attachments): void {
			$this->email->setAttachments($attachments);
		}

		public function appendToAttachment(string $path, string $name = null): void {
			$this->email->appendToAttachment($path, $name);
		}

		public function setSubject(string $subject): void {
			$this->email->setSubject($subject);
		}

		public function fixForNonProduction(): void {
			if (!Tenant::isProd()) {
				$replaced = [];

				if (!empty($this->getTo())) {
					$to = '';
					foreach ($this->getTo() as $toRes) {
						$to .= $toRes->getEmail() . ';';
					}
					$replaced[] = "To:" . $to;

					$this->clearTo();
					$this->appendTo('Test', EmailConfiguration::getTestEmail());
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

				$this->email->fixForNonProduction($replaced);
			}
		}

		public function validate(): void {
			if (empty($this->getTo())) {
				throw new EmptyValueException('Recepient');
			}

			$this->email->validate();
		}

	}
