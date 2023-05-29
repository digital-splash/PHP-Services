<?php
	namespace DigitalSplash\Notification\Models;

	class EmailConfiguration {
		private static string $HOST;
		private static string $PORT;
		private static string $USERNAME;
		private static string $PASSWORD;
		private static string $ENCRYPTION;
		private static string $FROMNAME;
		private static string $FROMEMAIL;

		public static function setHost(
			string $host
		): void {
			self::$HOST = $host;
		}
		public static function getHost(): string {
			return self::$HOST;
		}

		public static function setPort(
			string $var
		): void {
			self::$PORT = $var;
		}
		public static function getPort(): string {
			return self::$PORT;
		}

		public static function setUsername(
			string $var
		): void {
			self::$USERNAME = $var;
		}
		public static function getUsername(): string {
			return self::$USERNAME;
		}

		public static function setPassword(
			string $var
		): void {
			self::$PASSWORD = $var;
		}
		public static function getPassword(): string {
			return self::$PASSWORD;
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
			self::$FROMNAME = $var;
		}
		public static function getFromName(): string {
			return self::$FROMNAME;
		}

		public static function setFromEmail(
			string $var
		): void {
			self::$FROMEMAIL = $var;
		}
		public static function getFromEmail(): string {
			return self::$FROMEMAIL;
		}

	}