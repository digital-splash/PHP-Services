<?php
	use DigitalSplash\Notification\Helpers\Notification;
	use DigitalSplash\Notification\Models\Template;

	include_once __DIR__ . '/../../../../../vendor/autoload.php';


	$template = new Template(
		[
			'full_name' => 'Hadi Darwish',
			'button_text' => 'Test button',
			'url' => 'https://google.com'
		],
		true,
		'TestEmail'
	);

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
	$notification->model->email->setBody(
		$template->getContent()
	);

	$notificationResponse = $notification->send();
	var_dump($notificationResponse);
