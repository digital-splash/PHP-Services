<?php

use DigitalSplash\Exceptions\ConfigurationNotFoundException;
use DigitalSplash\Exceptions\InvalidConfigurationException;
use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Notification\Models\EmailConfiguration;

	$dir = __DIR__;
	$prevDir = '';
	while (!file_exists($dir . '/dgsplash.phpservices.env.json')) {
		$prevDir = $dir;
		$dir = dirname($dir);
		if ($dir === $prevDir) {
			throw new ConfigurationNotFoundException();
		}
	}

	$config = Helper::GetJsonContentFromFileAsArray(
		$dir . '/dgsplash.phpservices.env.json'
	);

	if (Helper::IsNullOrEmpty($config)) {
		throw new InvalidConfigurationException();
	}

	// Set the environment
	EmailConfiguration::setFromName($config['mail']['username']);
	EmailConfiguration::setFromEmail($config['mail']['email']);
	EmailConfiguration::setFromEmailPassword($config['mail']['password']);
	EmailConfiguration::setHost($config['mail']['host']);
	EmailConfiguration::setPort($config['mail']['port']);
	EmailConfiguration::setEncryption($config['mail']['encryption']);
	EmailConfiguration::setTestEmail($config['mail']['test_email']);
	EmailConfiguration::setIsProd($config['environment']);

	// Load the configuration file
	require_once $dir . '/dgsplash.phpservices.env.json';

