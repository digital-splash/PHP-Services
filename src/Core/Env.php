<?php

	namespace DigitalSplash\Core;

	use DigitalSplash\Exceptions\Configuration\ConfigurationNotFoundException;
	use DigitalSplash\Exceptions\Configuration\InvalidConfigurationException;
	use DigitalSplash\Helpers\Helper;

	class Env {
		const KEY_PROD = 'prod';
		const KEY_TEST = 'test';
		const KEY_LOCAL = 'local';
		public static string $envName;
		public static string $superPass;
		public static bool $enableNextGen;
		public static bool $debug;
		public static string $timezone;
		public static string $adminPath;
		public static string $apiPath;
		public static string $appPath;
		public static string $mediaPath;
		public static string $websitePath;
		public static string $serverAddress;
		public static string $urlScheme;
		public static bool $isPrint;
		public static string $urlFull;
		public static string $urlNoParams;
		public static string $getKey1;
		public static string $getKey2;
		public static string $getKey3;
		public static string $userIp;
		public static bool $isLocal;
		public static bool $isTest;
		public static bool $isProd;
		private static array $config = [];

		public static function init(
			string $envFileName = 'env.json'
		): void {
			self::getConfigFromFile($envFileName);
			self::setConfig();
		}

		public static function getByKey(string $key) {
			$keyArr = explode('.', $key);

			$config = self::$config;
			foreach ($keyArr as $k) {
				$config = $config[$k] ?? [];
			}

			if (Helper::isNullOrEmpty($config)) {
				$config = '';
			}

			return $config;
		}

		/**
		 * @throws ConfigurationNotFoundException
		 * @throws InvalidConfigurationException
		 */
		private static function getConfigFromFile(
			string $envFileName = 'env.json'
		): void {
			$dir = __DIR__;

			while (!file_exists("{$dir}/{$envFileName}")) {
				$prevDir = $dir;
				$dir = dirname($dir);
				if ($dir === $prevDir) {
					throw new ConfigurationNotFoundException('Configuration file "env.json" not found!');
				}
			}

			self::$config = Helper::getJsonContentFromFileAsArray(
				"{$dir}/{$envFileName}"
			);

			if (Helper::isNullOrEmpty(self::$config)) {
				throw new InvalidConfigurationException();
			}
		}

		private static function setConfig(): void {
			self::setMainVars();

			self::setTenantConfig();
			self::setDatabaseConfig();
			self::setPathsConfig();
			self::setEmailConfig();
			self::setApiNinjasConfig();
			self::setCacheConfig();

			self::setEnvironment();
			self::setOtherVars();
		}

		private static function setMainVars(): void {
			self::$envName = self::getByKey('env');
			self::$superPass = self::getByKey('super_pass');
			self::$enableNextGen = Helper::ConvertToBool(self::getByKey('enable_next_gen'));
			self::$debug = Helper::ConvertToBool(self::getByKey('debug'));
			self::$timezone = self::getByKey('timezone');

			if (!defined('PHPUNIT_TEST_SUITE')) {
				define('PHPUNIT_TEST_SUITE', 0);
			}
		}

		private static function setTenantConfig(): void {
//			$config = self::$config['tenant'] ?? [];
//
//			Tenant::setEnvironment(self::$config['env'] ?? Tenant::ENV_LOCAL);
//
//			Tenant::setName($config['name'] ?? '');
//			Tenant::setDomain($config['domain'] ?? '');
//			Tenant::setYear($config['year'] ?? '');
//			Tenant::setLogo($config['logo'] ?? '');
//			Tenant::setPrimaryColor($config['primary_color'] ?? '');
//			Tenant::setSecondaryColor($config['secondary_color'] ?? '');
		}

		private static function setDatabaseConfig(): void {
//			$database = self::$config['database'] ?? [];
//			$mysql = $database['mysql'] ?? [];
//
//			DbConn::setMysqlDbHost($mysql['host'] ?? '');
//			DbConn::setMysqlDbUser($mysql['username'] ?? '');
//			DbConn::setMysqlDbPass($mysql['password'] ?? '');
//			DbConn::setMysqlDbMain($mysql['main_database'] ?? '');
//			DbConn::setMysqlDbLogs($mysql['logs_database'] ?? '');
//			DbConn::setMysqlDbMainTest($mysql['test_main_database'] ?? '');
//			DbConn::setMysqlDbLogsTest($mysql['test_logs_database'] ?? '');

//			if (PHPUNIT_TEST_SUITE === 1) {
//				DbConn::setPhpUnitTestSuite(0);
//			} else {
//				DbConn::setPhpUnitTestSuite(1);
//			}
		}

		private static function setPathsConfig(): void {
			self::$adminPath = self::getByKey('paths.admin');
			self::$apiPath = self::getByKey('paths.api');
			self::$appPath = self::getByKey('paths.app');
			self::$mediaPath = self::getByKey('paths.media');
			self::$websitePath = self::getByKey('paths.website');
		}

		private static function setEmailConfig(): void {
//			$config = self::$config['mail'] ?? [];
//
//			EmailConfiguration::setHost($config['host'] ?? '');
//			EmailConfiguration::setPort($config['port'] ?? 0);
//			EmailConfiguration::setEncryption($config['encryption'] ?? '');
//			EmailConfiguration::setFromName($config['from']['name'] ?? Tenant::getName() ?? '');
//			EmailConfiguration::setFromEmail($config['from']['email'] ?? '');
//			EmailConfiguration::setFromEmailPassword($config['from']['password'] ?? '');
//			EmailConfiguration::setTestEmail($config['test_email'] ?? '');
		}

		private static function setApiNinjasConfig(): void {
//			$config = self::$config['api_ninjas'] ?? [];
//
//			ApiNinjas::setApiKey($config['key'] ?? '');
//			ApiNinjas::setApiUrl($config['url'] ?? '');
		}

		private static function setCacheConfig(): void {
//			$config = self::$config['cache'] ?? [];
//
//			$rootFolder = '';
//			if (!Helper::IsNullOrEmpty($config['root_src'] ?? '')) {
//				$rootFolder = $config['root_src'];
//			}
//			if (!Helper::IsNullOrEmpty($config['root_src_const_name'] ?? '') && defined($config['root_src_const_name'])) {
//				$rootFolder = constant($config['root_src_const_name']);
//			}
//
//			// $rootFolder = !empty($config['root_src_const_name']) ? constant($config['root_src_const_name']) : '';
//			ServerCache::setRootFolder($rootFolder);
//			ServerCache::setCacheFolderName($config['folder_name'] ?? '');
		}

		private static function setOtherVars(): void {
			self::$serverAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['LOCAL_ADDR'] ?? '::1';
			self::$urlScheme = !Helper::IsNullOrEmpty($_SERVER['REQUEST_SCHEME'] ?? '') ? $_SERVER['REQUEST_SCHEME'] : 'http';
			self::$isPrint = Helper::ConvertToBool(isset($_GET['print']) && $_GET['print'] == 1);
			self::$urlFull = ($_SERVER['SERVER_NAME'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
			self::$urlNoParams = '';

			if (!Helper::isNullOrEmpty(self::$urlFull)) {
				self::$urlFull = self::$urlScheme . '://' . self::$urlFull;

				$urlParts = parse_url(self::$urlFull) ?? '';
				if (is_array($urlParts) && !Helper::IsNullOrEmpty($urlParts)) {
					self::$urlNoParams = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '/';
				}
			}

			self::$getKey1 = $_REQUEST['k1'] ?? '';
			self::$getKey2 = $_REQUEST['k2'] ?? '';
			self::$getKey3 = $_REQUEST['k3'] ?? '';

			self::$userIp = $_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
		}

		private static function setEnvironment(): void {
			self::$isLocal = false;
			self::$isTest = false;
			self::$isProd = false;

			switch (self::$envName) {
				case self::KEY_PROD:
					self::$isProd = true;
					break;

				case self::KEY_TEST:
					self::$isTest = true;
					break;

				case self::KEY_LOCAL:
				default:
					self::$isLocal = true;
					break;
			}
		}
	}