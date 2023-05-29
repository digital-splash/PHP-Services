<?php

	include_once __DIR__ . '/../../../../../vendor/autoload.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	$mail = new PHPMailer(true);

	try {
		//Server settings
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->Host = 'mail.dgsplash.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'Email@dgsplash.com';
		$mail->Password = 'Password';
		$mail->SMTPSecure = 'ssl';
		$mail->Port = '465';

		//Recipients
		$mail->setFrom('noreply@dgsplash.com', 'noreply@dgsplash.com');
		$mail->addAddress('hadidarwish@dgsplash.com', 'Hadi Darwish');//Add a recipient
		$mail->addAddress('hadidarwish222@gmail.com', 'Hadi Darwish');//Add a recipient
		// $mail->addAddress('ellen@example.com'); //Name is optional
		$mail->addReplyTo('hadidarwish@dgsplash.com', 'Information');
		$mail->addCC('hadi.darwish.03@gmail.com');
		$mail->addBCC('hadidarwish222@gmail.com');

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
		echo 'Message has been sent';
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}