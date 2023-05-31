<?php
	use DigitalSplash\Notification\Helpers\Notification;
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

	$notification = new Notification();

	$notification->setSendEmail(true);

	$notification->model->appendTo('Testing Email', 'hadidarwish999@gmail.com');
	$notification->model->appendTo('Hadi Darwish', 'hadidarwish222@gmail.com');
	$notification->model->appendCC('Hadi','hadidarwish999@gmail.com');
	$notification->model->appendBCC('Hadi Darwish','hadidarwish222@gmail.com');
	$notification->model->appendToAttachment(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.jpg", 'user-01.jpg');
	$notification->model->appendToAttachment(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.jpg", 'user-01-th.jpg');
	$notification->model->appendToAttachment(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.webp", 'user-01.webp');
	$notification->model->setSubject('Testing Email');
	$notification->model->email->setBody('This is a test email');

	$notificationResponse = $notification->send();

	var_dump($notificationResponse);
