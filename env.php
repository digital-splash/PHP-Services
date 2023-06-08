<?php
	use DigitalSplash\Exceptions\Configuration\ConfigurationNotFoundException;
	use DigitalSplash\Exceptions\Configuration\InvalidConfigurationException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Models\Tenant;
	use DigitalSplash\Notification\Models\EmailConfiguration;

	class EnvConfig {

		private static array $config = [];

		public static function init(): void {
			self::setConfig();
			self::getConfigFromFile();
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
			self::tenantConfig();
			self::emailConfig();
		}

		private static function tenantConfig(): void {
			$config = self::$config['tenant'] ?: [];

			Tenant::setEnvironment(self::$config['environment'] ?: 'local');

			Tenant::setName($config['name'] ?: '');
			Tenant::setDomain($config['domain'] ?: '');
			Tenant::setYear($config['year'] ?: '');
			Tenant::setLogo($config['logo'] ?: '');
			Tenant::setPrimaryColor($config['primary_color'] ?: '');
			Tenant::setSecondaryColor($config['secondary_color'] ?: '');
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

			//TODO: template...
		}

	}

	EnvConfig::init();
