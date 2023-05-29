<?php
	use DigitalSplash\Notification\Helpers\Email\Email;

	include_once __DIR__ . '/../../../../../vendor/autoload.php';

	$email = new Email();
	$email->model->appendTo('Testing Email', 'testing@dgsplash.com');
	$email->send();
