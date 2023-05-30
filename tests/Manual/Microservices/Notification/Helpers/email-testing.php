<?php
	use DigitalSplash\Notification\Helpers\Email\Email;
	use DigitalSplash\Notification\Models\EmailConfiguration;

	include_once __DIR__ . '/../../../../../vendor/autoload.php';

	EmailConfiguration::setIsProd(false);
	EmailConfiguration::setHost('mail.dgsplash.com');
	EmailConfiguration::setPort(465);
	EmailConfiguration::setEncryption('ssl');
	EmailConfiguration::setFromName('Digital Splash');
	EmailConfiguration::setFromEmail('noreply@dgsplash.com');
	EmailConfiguration::setFromEmailPassword('%E;Pw&p4#3gd8i0Y?{');
	EmailConfiguration::setTestEmail('testing@dgsplash.com');

	$email = new Email();
	$email->model->appendTo('Testing Email', 'hadidarwish999@gmail.com');
	$email->model->appendTo('Hadi Darwish', 'hadidarwish222@gmail.com');
	$email->model->setSubject('Testing Email');
	$email->model->setBody('This is a test email');
	$email->model->appendCC('Hadi','hadidarwish999@gmail.com');
	$email->model->appendBCC('Hadi Darwish','hadidarwish222@gmail.com');
	$email->model->appendToAttachment(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.jpg", 'test.jpg');

	$email->send();
