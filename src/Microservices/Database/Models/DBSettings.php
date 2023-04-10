<?php
	namespace DigitalSplash\Database\Models;

	class DBSettings {
		public const ENGINE_MYSQL = 'mysql';

		public const TYPE_MAIN = 'main';
		public const TYPE_LOGS = 'logs';

		public const CREDENTIALS_KEY_HOST = 'host';
		public const CREDENTIALS_KEY_PORT = 'port';
		public const CREDENTIALS_KEY_USERNAME = 'username';
		public const CREDENTIALS_KEY_PASSWORD = 'password';
		public const CREDENTIALS_KEY_DATABASE = 'database';
		public const CREDENTIALS_KEY_CHARSET = 'charset';

		public const KEY_READS = 'reads';
		public const KEY_WRITES = 'writes';
		public const KEY_COUNTS = 'counts';
	}
