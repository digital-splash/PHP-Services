<?php
	namespace DigitalSplash\Notification\Models;

	class EmailConfiguration {
		private static bool $IS_PROD = false;
		private static string $HOST = '';
		private static int $PORT = 0;
		private static string $ENCRYPTION = '';
		private static string $FROM_NAME = '';
		private static string $FROM_EMAIL = '';
		private static string $FROM_EMAIL_PASSWORD = '';
		private static string $TEST_EMAIL = '';

		public static function setIsProd(
			bool $var
		): void {
			self::$IS_PROD = $var;
		}

		public static function getIsProd(): bool {
			return self::$IS_PROD;
		}

		public static function setHost(
			string $host
		): void {
			self::$HOST = $host;
		}

		public static function getHost(): string {
			return self::$HOST;
		}

		public static function setPort(
			int $var
		): void {
			self::$PORT = $var;
		}

		public static function getPort(): int {
			return self::$PORT;
		}

		public static function setEncryption(
			string $var
		): void {
			self::$ENCRYPTION = $var;
		}

		public static function getEncryption(): string {
			return self::$ENCRYPTION;
		}

		public static function setFromName(
			string $var
		): void {
			self::$FROM_NAME = $var;
		}

		public static function getFromName(): string {
			return self::$FROM_NAME;
		}

		public static function setFromEmail(
			string $var
		): void {
			self::$FROM_EMAIL = $var;
		}

		public static function getFromEmail(): string {
			return self::$FROM_EMAIL;
		}

		public static function setFromEmailPassword(
			string $var
		): void {
			self::$FROM_EMAIL_PASSWORD = $var;
		}

		public static function getFromEmailPassword(): string {
			return self::$FROM_EMAIL_PASSWORD;
		}

		public static function setTestEmail(
			string $var
		): void {
			self::$TEST_EMAIL = $var;
		}

		public static function getTestEmail(): string {
			return self::$TEST_EMAIL;
		}

	}
