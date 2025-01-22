<?php
	namespace DigitalSplash\Notification\Models;

	class EmailConfiguration {
		private static string $host = '';
		private static int $port = 0;
		private static string $encryption = '';
		private static string $fromName = '';
		private static string $fromEmail = '';
		private static string $fromEmailPassword = '';
		private static string $testEmail = '';

		public static function setHost(
			string $host
		): void {
			self::$host = $host;
		}

		public static function getHost(): string {
			return self::$host;
		}

		public static function setPort(
			int $var
		): void {
			self::$port = $var;
		}

		public static function getPort(): int {
			return self::$port;
		}

		public static function setEncryption(
			string $var
		): void {
			self::$encryption = $var;
		}

		public static function getEncryption(): string {
			return self::$encryption;
		}

		public static function setFromName(
			string $var
		): void {
			self::$fromName = $var;
		}

		public static function getFromName(): string {
			return self::$fromName;
		}

		public static function setFromEmail(
			string $var
		): void {
			self::$fromEmail = $var;
		}

		public static function getFromEmail(): string {
			return self::$fromEmail;
		}

		public static function setFromEmailPassword(
			string $var
		): void {
			self::$fromEmailPassword = $var;
		}

		public static function getFromEmailPassword(): string {
			return self::$fromEmailPassword;
		}

		public static function setTestEmail(
			string $var
		): void {
			self::$testEmail = $var;
		}

		public static function getTestEmail(): string {
			return self::$testEmail;
		}

	}
