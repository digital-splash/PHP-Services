<?php
	namespace DigitalSplash\Notification\Helpers\Email;

	use DigitalSplash\Notification\Interfaces\IEmail;
	use DigitalSplash\Notification\Models\Email\Email as EmailModel;
	use PHPMailer\PHPMailer\PHPMailer as MainPHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	class PhpMailer implements IEmail {
		public EmailModel $model;

		public function __construct() {
			$this->model = new EmailModel();
		}

		public function send(): void {
			$mail = new MainPHPMailer(true);

			try {
				//Server settings
				$mail->SMTPDebug = SMTP::DEBUG_SERVER;
				$mail->isSMTP();
				$mail->Host = 'mail.dgsplash.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'noreply@dgsplash.com';
				$mail->Password = '%E;Pw&p4#3gd8i0Y?{';
				$mail->SMTPSecure = 'ssl';
				$mail->Port = '465';

				//Recipients
				$mail->setFrom('noreply@dgsplash.com', 'Digital Splash');
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
				$mail->Subject = 'Subject ';
				$mail->Body = $body;
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients\n' . strip_tags($body);

				$mail->send();
				var_dump($mail);
			} catch (Exception $e) {
				var_dump($mail);
				echo "<hr />";
				var_dump($e);
				// echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}
