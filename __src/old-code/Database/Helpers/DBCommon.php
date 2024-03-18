<?php
	namespace OldCode\DigitalSplash\Database\Helpers;

	use DigitalSplash\Database\Models\DBSettings;
	use DigitalSplash\Helpers\Helper;

	class DBCommon {
		private string $engine;
		protected string $host;
		protected ?string $port;
		protected string $username;
		protected string $password;
		protected string $database;

		protected string $hash;
		protected static bool $deadlock_reported = false;
		protected array $cached_vars = [];

		protected static ?self $instanceMain = null;
		protected static ?self $tempInstanceMain = null;

		protected static ?self $instanceLogs = null;
		protected static ?self $tempInstanceLogs = null;

		protected static array $query_log = [];
		protected static array $query_times = [];

		protected function __construct(
			string $engine,
			string $host,
			?string $port,
			string $username,
			string $password,
			string $database
		) {
			$tempPort = DBHelper::GetPortFromHost($host);
			if (!Helper::IsNullOrEmpty($tempPort)) {
				$port = $tempPort;
			}

			$this->engine = $engine;
			$this->host = $host;
			$this->port = $port;
			$this->username = $username;
			$this->password = $password;
			$this->database = $database;

			$this->hash = DBHelper::calculateHash(
				$this->engine,
				$this->host,
				$this->username,
				$this->password,
				$this->database,
				$this->port
			);
		}

		/**
		 * Logs a query (used in Debug mode) by appending it to an array along with
		 * the time, stack trace and other useful information.
		 */
		public static function log_query(
			string $query,
			int $time
		): void {
			$query = trim($query);

			if (Helper::StringBeginsWith(strtoupper($query), 'SELECT')) {
				if (!array_key_exists(DBSettings::KEY_READS, self::$query_times)) {
					self::$query_times[DBSettings::KEY_READS] = 1;
				} else {
					self::$query_times[DBSettings::KEY_READS]++;
				}
			} else if (
				Helper::StringBeginsWith(strtoupper($query), 'INSERT') ||
				Helper::StringBeginsWith(strtoupper($query), 'UPDATE')
			) {
				if (!array_key_exists(DBSettings::KEY_WRITES, self::$query_times)) {
					self::$query_times[DBSettings::KEY_WRITES] = 1;
				} else {
					self::$query_times[DBSettings::KEY_WRITES]++;
				}
			}

			if (!array_key_exists(DBSettings::KEY_COUNTS, self::$query_times)) {
				self::$query_times[DBSettings::KEY_COUNTS] = [];
			}
			if (!isset(self::$query_times[DBSettings::KEY_COUNTS][$query])) {
				self::$query_times[DBSettings::KEY_COUNTS][$query] = 1;
				self::$query_log[] = [
					'query' => $query,
					'time' => $time,
					'trace' => debug_backtrace(),
					'connector' => 'PDO'
				];
			} else {
				self::$query_times[DBSettings::KEY_COUNTS][$query]++;
			}
		}

	}
