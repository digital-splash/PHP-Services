<?php
	use DigitalSplash\Exceptions\Configuration\ConfigurationNotFoundException;
	use DigitalSplash\Exceptions\Configuration\InvalidConfigurationException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Notification\Models\EmailConfiguration;

	class EnvConfig {

		private static array $config = [];

		public static function init(): void {
			self::getConfigFromFile();
			self::setConfig();
		}

		private static function getConfigFromFile(): void {
			$dir = __DIR__;
			$prevDir = '';
			while (!file_exists($dir . '/dgsplash.phpservices.env.json')) {
				$prevDir = $dir;
				$dir = dirname($dir);
				if ($dir === $prevDir) {
					throw new ConfigurationNotFoundException();
				}
			}

			self::$config = Helper::GetJsonContentFromFileAsArray(
				$dir . '/dgsplash.phpservices.env.json'
			);

			if (Helper::IsNullOrEmpty(self::$config)) {
				throw new InvalidConfigurationException();
			}

		}

		private static function setConfig(): void {
			EmailConfiguration::setIsProd(self::$config['environment'] === 'production');

			self::emailConfig();
		}

		private static function emailConfig(): void {
			$config = self::$config['mail'] ?: [];

			EmailConfiguration::setHost($config['host'] ?: '');
			EmailConfiguration::setPort($config['port'] ?: 0);
			EmailConfiguration::setEncryption($config['encryption'] ?: '');
			EmailConfiguration::setFromName($config['from']['name'] ?: '');
			EmailConfiguration::setFromEmail($config['from']['email'] ?: '');
			EmailConfiguration::setFromEmailPassword($config['from']['password'] ?: '');
			EmailConfiguration::setTestEmail($config['test_email'] ?: '');
		}
	}

	EnvConfig::init();
