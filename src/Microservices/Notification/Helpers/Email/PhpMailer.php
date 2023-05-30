<?php
	namespace DigitalSplash\Notification\Helpers\Email;

	use DigitalSplash\Notification\Interfaces\IEmail;
	use DigitalSplash\Notification\Models\Email as EmailModel;
	use DigitalSplash\Notification\Models\EmailConfiguration;
	use PHPMailer\PHPMailer\PHPMailer as MainPHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	class PhpMailer implements IEmail {
		public EmailModel $model;

		public function __construct() {
			$this->model = new EmailModel();
		}

		public function send(): void {
			try {
				$mail = new MainPHPMailer(true);
				$mail->isSMTP();
				$mail->SMTPDebug = EmailConfiguration::getIsProd() ? SMTP::DEBUG_OFF : SMTP::DEBUG_SERVER;
				$mail->SMTPAuth = true;
				$mail->Host = EmailConfiguration::getHost();
				$mail->Port = EmailConfiguration::getPort();
				$mail->SMTPSecure = EmailConfiguration::getEncryption();
				$mail->Username = EmailConfiguration::getFromEmail();
				$mail->Password = EmailConfiguration::getFromEmailPassword();

				//Recipients
				$mail->setFrom(
					EmailConfiguration::getFromEmail(),
					EmailConfiguration::getFromName()
				);

				foreach ($this->model->getTo() as $recepient) {
					$mail->addAddress($recepient->getEmail(), $recepient->getName());
				}
				// $mail->addReplyTo('hadidarwish@dgsplash.com', 'Information');
				// $mail->addCC('hadi.darwish.03@gmail.com');
				// $mail->addBCC('hadidarwish222@gmail.com');

				//Attachments
				// $mail->addAttachment( __DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.jpg",'new.jpg');//Add attachments
				// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');//Optional name

				//Content
				$body = '
					<h1>Test</h1>
					<p>Test</p>
					This is the HTML message body <b>in bold!</b>
					';

				$mail->isHTML(true);//Set email format to HTML
				$mail->Subject = $this->model->getSubject();
				$mail->Body = $this->model->getBody();
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients\n' . strip_tags($body);


				// if (!EmailConfiguration::getIsProd()) {
				// 	//replace all emails by the test email and add them to the subject
				// 	$subject = $mail->Subject;
				// 	//add all adresses to the subject
				// 	foreach ($this->model->getTo() as $address) {
				// 		$subject .= " - " . $address->getEmail();
				// 	}
				// 	$mail->Subject = $subject;
				// 	$mail->clearAddresses();
				// 	$mail->addAddress($this->model->getTestEmail());
				// }
				// $mail->send();
				// var_dump($mail);
			} catch (Exception $e) {
				//TODO: Create Notification Exceptions, and call it from here...
				//throw new PhpMailerException($e->getMessage());
			}
		}
	}
