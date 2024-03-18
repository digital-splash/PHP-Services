<?php
	namespace OldCode\DigitalSplash\Database\Models;

	class DBCredentials {
		private static array $credentials = [];
		private static string $engine = 'db_engine';
		private static string $databaseType = 'db_type';

		public static function setEngine(
			string $engine
		): void {
			self::$engine = $engine;
		}

		public static function getEngine(): string {
			return self::$engine;
		}

		public static function setDatabaseType(
			string $databaseType
		): void {
			self::$databaseType = $databaseType;
		}

		public static function getDatabaseType(): string {
			return self::$databaseType;
		}

		private static function checkEngineAndDatabaseType(): void {
			if (!array_key_exists(self::$engine, self::$credentials)) {
				self::$credentials[self::$engine] = [];
			}
			if (!array_key_exists(self::$databaseType, self::$credentials[self::$engine])) {
				self::$credentials[self::$engine][self::$databaseType] = [];
			}
		}

		public static function setHost(string $host): void {
			self::checkEngineAndDatabaseType();
			self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_HOST] = $host;
		}

		public static function getHost(): string {
			return self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_HOST];
		}

		public static function setPort(
			string $port
		): void {
			self::checkEngineAndDatabaseType();
			self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_PORT] = $port;
		}

		public static function getPort(): string {
			return self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_PORT];
		}

		public static function setUsername(
			string $username
		): void {
			self::checkEngineAndDatabaseType();
			self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_USERNAME] = $username;
		}

		public static function getUsername(): string {
			return self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_USERNAME];
		}

		public static function setPassword(
			string $password
		): void {
			self::checkEngineAndDatabaseType();
			self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_PASSWORD] = $password;
		}

		public static function getPassword(): string {
			return self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_PASSWORD];
		}

		public static function setDatabase(
			string $database
		): void {
			self::checkEngineAndDatabaseType();
			self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_DATABASE] = $database;
		}

		public static function getDatabase(): string {
			return self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_DATABASE];
		}

		public static function setCharset(
			string $charset
		): void {
			self::checkEngineAndDatabaseType();
			self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_CHARSET] = $charset;
		}

		public static function getCharset(): string {
			return self::$credentials[self::$engine][self::$databaseType][DBSettings::CREDENTIALS_KEY_CHARSET];
		}

	}