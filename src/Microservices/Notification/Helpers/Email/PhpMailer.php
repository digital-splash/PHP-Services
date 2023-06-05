<?php
	namespace DigitalSplash\Notification\Helpers\Email;

	use DigitalSplash\Exceptions\Notification\PhpMailerException;
use DigitalSplash\Helpers\Helper;
use DigitalSplash\Notification\Interfaces\IEmail;
	use DigitalSplash\Notification\Models\Notification as NotificationModel;
	use DigitalSplash\Notification\Models\EmailConfiguration;
	use PHPMailer\PHPMailer\PHPMailer as MainPHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	class PhpMailer implements IEmail {
		public NotificationModel $model;

		public function __construct() {
			$this->model = new NotificationModel();
		}

		public function send(): void {
			try {
				self::validateCredentials();
				$this->model->validate();
				$this->model->fixForNonProduction();

				$mail = new MainPHPMailer(true);
				$mail->isSMTP();
				$mail->SMTPDebug = SMTP::DEBUG_OFF;
				$mail->SMTPAuth = true;
				$mail->Host = EmailConfiguration::getHost();
				$mail->Port = EmailConfiguration::getPort();
				$mail->SMTPSecure = EmailConfiguration::getEncryption();
				$mail->Username = EmailConfiguration::getFromEmail();
				$mail->Password = EmailConfiguration::getFromEmailPassword();

				$mail->setFrom(
					EmailConfiguration::getFromEmail(),
					EmailConfiguration::getFromName()
				);

				foreach ($this->model->getTo() as $recipient) {
					$mail->addAddress($recipient->getEmail(), $recipient->getName());
				}
				foreach ($this->model->getCC() as $cc) {
					$mail->addCC($cc->getEmail(), $cc->getName());
				}
				foreach ($this->model->getBCC() as $bcc) {
					$mail->addBCC($bcc->getEmail(), $bcc->getName());
				}
				foreach ($this->model->getReplyTo() as $replyTo) {
					$mail->addReplyTo($replyTo->getEmail(), $replyTo->getName());
				}

				foreach ($this->model->email->getAttachments() as $attachment) {
					$mail->addAttachment($attachment['path'], $attachment['name']);
				}

				$mail->isHTML(true);//Set email format to HTML
				$mail->Subject = $this->model->email->getSubject();
				$mail->Body = $this->model->email->getBody();
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients\n' . strip_tags($mail->Body);
				$mail->send();
			} catch (Exception $e) {
				throw new PhpMailerException($e->getMessage());
			}
		}

		private function validateCredentials(): void {
			if (Helper::IsNullOrEmpty(EmailConfiguration::getFromEmail())) {
				throw new PhpMailerException('From email is not set');
			}
			if (Helper::IsNullOrEmpty(EmailConfiguration::getFromEmailPassword())) {
				throw new PhpMailerException('From email password is not set');
			}
			if (Helper::IsNullOrEmpty(EmailConfiguration::getHost())) {
				throw new PhpMailerException('SMTP host is not set');
			}
			if (Helper::IsNullOrEmpty(EmailConfiguration::getPort())) {
				throw new PhpMailerException('SMTP port is not set');
			}
			if (Helper::IsNullOrEmpty(EmailConfiguration::getEncryption())) {
				throw new PhpMailerException('SMTP encryption is not set');
			}
		}
	}
