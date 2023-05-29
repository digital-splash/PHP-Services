<?php
	use DigitalSplash\Notification\Helpers\Email\Email;
	use DigitalSplash\Notification\Models\EmailConfiguration;

	include_once __DIR__ . '/../../../../../vendor/autoload.php';

	EmailConfiguration::setHost('Put Host Here');
	EmailConfiguration::setPort(Put Port here);
	EmailConfiguration::setUsername('Put username here');
	EmailConfiguration::setPassword('Put password here');
	EmailConfiguration::setEncryption('Put wncryption here');
	EmailConfiguration::setFromName('Testing Email');


	$email = new Email();
	$email->model->setTestEmail('Put testing email');
	$email->model->appendTo('Testing Email', 'add a recipient ');
	$email->model->appendTo('Hadi Darwish', 'add a recipient ');
	$email->model->setSubject('Testing Email');
	$email->model->setBody('This is a test email');
	$email->model->appendCC('Testing Email','add cc');
	$email->model->appendBCC('Hadi Darwish','add bcc');

	$email->send();
