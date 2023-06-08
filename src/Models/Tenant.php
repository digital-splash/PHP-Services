<?php
	namespace DigitalSplash\Models;

	class Tenant {
		const ENV_PROD = 'production';
		const ENV_STAGING = 'staging';
		const ENV_TEST = 'test';
		const ENV_DEV = 'dev';
		const ENV_DEMO = 'demo';
		const ENV_LOCAL = 'local';

		private static $env;
		private static $name;
		private static $domain;
		private static $year;
		private static $logo;
		private static $primaryColor;
		private static $secondaryColor;

		public static function setEnvironment(string $env): void {
			self::$env = $env;
		}

		public static function getEnvironment(): string {
			return self::$env;
		}

		public static function setName(string $name): void {
			self::$name = $name;
		}

		public static function getName(): string {
			return self::$name;
		}

		public static function setDomain(string $domain): void {
			self::$domain = $domain;
		}

		public static function getDomain(): string {
			return self::$domain;
		}

		public static function setYear(string $year): void {
			self::$year = $year;
		}

		public static function getYear(): string {
			return self::$year;
		}

		public static function setLogo(string $logo): void {
			self::$logo = $logo;
		}

		public static function getLogo(): string {
			return self::$logo;
		}

		public static function setPrimaryColor(string $color): void {
			self::$primaryColor = $color;
		}

		public static function getPrimaryColor(): string {
			return self::$primaryColor;
		}

		public static function setSecondaryColor(string $color): void {
			self::$secondaryColor = $color;
		}

		public static function getSecondaryColor(): string {
			return self::$secondaryColor;
		}

		public static function isProd(): bool {
			return self::$env === self::ENV_PROD;
		}

	}
