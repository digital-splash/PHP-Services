<?php
	namespace DigitalSplash\Helpers;

	class Settings {
		public static string $envName;
		public static string $superPass;
		public static bool $enableNextGen;
		public static bool $debug;
		public static string $timezone;

		public static string $serverAddress;
		public static string $urlScheme;
		public static bool $isPrint;
		public static string $thisUrl;
		public static string $thisUrlNoParams;
		public static string $getKey1;
		public static string $getKey2;
		public static string $getKey3;

		public static bool $isLocalEnv;
		public static bool $isTestEnv;
		public static bool $isProdEnv;

		public static string $adminPath;
		public static string $apiPath;
		public static string $appPath;
		public static string $mediaPath;
		public static string $websitePath;

		public static string $userIp;

		public static function init(): void {
			self::setMainVars();
			self::setOtherVars();
			self::setEnvironment();
			self::setPaths();

			self::SetPostVars();
		}

		private static function setMainVars(): void {
			self::$envName = EnvConfig::getByKey('env');
			self::$superPass = EnvConfig::getByKey('super_pass');
			self::$enableNextGen = Helper::ConvertToBool(EnvConfig::getByKey('enable_next_gen'));
			self::$debug = Helper::ConvertToBool(EnvConfig::getByKey('debug'));
			self::$timezone = EnvConfig::getByKey('timezone');
		}

		private static function setOtherVars(): void {
			self::$serverAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['LOCAL_ADDR'] ?? '::1';
			self::$urlScheme = !Helper::IsNullOrEmpty($_SERVER['REQUEST_SCHEME'] ?? '') ? $_SERVER['REQUEST_SCHEME'] : 'http';
			self::$isPrint = Helper::ConvertToBool(isset($_GET['print']) && $_GET['print'] == 1);
			self::$thisUrl = self::$urlScheme . '://' . ($_SERVER['SERVER_NAME'] ?? 'SERVER_NAME') . ($_SERVER['REQUEST_URI'] ?? 'REQUEST_URI');

			$urlParts = parse_url(self::$thisUrl) ?? '';
			self::$thisUrlNoParams = '';
			if (is_array($urlParts) && !Helper::IsNullOrEmpty($urlParts)) {
				self::$thisUrlNoParams = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '/';
			}

			self::$getKey1 = $_REQUEST['k1'] ?? '';
			self::$getKey2 = $_REQUEST['k2'] ?? '';
			self::$getKey3 = $_REQUEST['k3'] ?? '';
		}

		private static function setEnvironment(): void {
			self::$isLocalEnv = false;
			self::$isTestEnv = false;
			self::$isProdEnv = false;

			switch (EnvConfig::getByKey('env')) {
				case 'prod':
					self::$isProdEnv = true;
					break;

				case 'test':
					self::$isTestEnv = true;
					break;

				default:
					self::$isLocalEnv = true;
					break;
			}
		}

		private static function setPaths(): void {
			self::$adminPath = EnvConfig::getByKey('paths.admin');
			self::$apiPath = EnvConfig::getByKey('paths.api');
			self::$appPath = EnvConfig::getByKey('paths.app');
			self::$mediaPath = EnvConfig::getByKey('paths.media');
			self::$websitePath = EnvConfig::getByKey('paths.website');
		}

		private static function setPostVars(): void {
			self::$userIp = $_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

	}
