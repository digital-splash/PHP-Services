<?php
	namespace DigitalSplash\Database\MySQL\Helpers;

	use Closure;
	use DigitalSplash\Database\Helpers\DBCommon;
	use DigitalSplash\Database\Models\DBSettings;
	use DigitalSplash\Database\Models\DBCredentials;
	use DigitalSplash\Exceptions\Database\DatabaseInvalidConnectorException;
	use DigitalSplash\Helpers\Helper;
	use PDO;
	use PDOException;
	use Throwable;

	class PDODataDB extends DBCommon {
		private PDO $pdo;

		protected static ?self $_instance_main = null;
		protected static ?self $_temp_instance_main = null;

		protected static ?self $_instance_logs = null;
		protected static ?self $_temp_instance_logs = null;

		protected function __construct(
			string $host,
			?string $port,
			string $username,
			string $password,
			string $database
		) {
			parent::__construct(
				DBSettings::ENGINE_MYSQL,
				$host,
				$port,
				$username,
				$password,
				$database
			);
			$this->pdo = $this->createPDO();
		}

		private function createPDO(): PDO {
			try {
				$pdo = new PDO(
					$this->createDsn(),
					$this->username,
					$this->password
				);
				$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

				return $pdo;
			} catch (PDOException $e) {
				die('Connection failed: ' . $e->getMessage());
			}
		}

		private function createDsn(): string {
			$dnsArr = [
				'host=' . $this->host,
				Helper::StringNullOrEmpty($this->port) ? '' : 'port=' . $this->port,
				'dbname=' . $this->database,
			];
			return DBSettings::ENGINE_MYSQL . ':' . Helper::ImplodeArrToStr($dnsArr, ';');
		}

		public static function getInstance(): self {
			switch (DBCredentials::getDatabaseType()) {
				case DBSettings::TYPE_LOGS:
					return self::getLogsInstance();

				default:
					return self::getMainInstance();
			}
		}

		private static function getMainInstance(): self {
			DBCredentials::setEngine(DBSettings::ENGINE_MYSQL);
			DBCredentials::setDatabaseType(DBSettings::TYPE_MAIN);

			$host = DBCredentials::getHost();
			$port = DBCredentials::getPort();
			$username = DBCredentials::getUsername();
			$password = DBCredentials::getPassword();
			$database = DBCredentials::getDatabase();

			if (self::$_instance_main === null) {
				self::$_instance_main = new self(
					$host,
					$port,
					$username,
					$password,
					$database
				);
			}

			if (self::$_instance_main->pdo === null) {
				throw new DatabaseInvalidConnectorException(Helper::ImplodeArrToStr([
					$host,
					$port,
					$username,
					$password,
					$database
				], ';'));
			}

			return self::$_instance_main;
		}

		private static function getLogsInstance(): self {
			DBCredentials::setEngine(DBSettings::ENGINE_MYSQL);
			DBCredentials::setDatabaseType(DBSettings::TYPE_LOGS);

			$host = DBCredentials::getHost();
			$port = DBCredentials::getPort();
			$username = DBCredentials::getUsername();
			$password = DBCredentials::getPassword();
			$database = DBCredentials::getDatabase();

			if (self::$_instance_logs === null) {
				self::$_instance_logs = new self(
					$host,
					$port,
					$username,
					$password,
					$database
				);
			}

			if (self::$_instance_logs->pdo === null) {
				throw new DatabaseInvalidConnectorException(Helper::ImplodeArrToStr([
					$host,
					$port,
					$username,
					$password,
					$database
				], ';'));
			}

			return self::$_instance_logs;
		}

		/**
		 * Execute a Closure within a transaction. Can be used in conjunction with unit tests that use transactions
		 * in their setup.
		 */
		public function transaction(Closure $callback): mixed {
			$this->beginTransaction();
			try {
				$result = $callback();
				$this->commit();

				return $result;
			} catch (Throwable $exception) {
				$this->rollBack();
				throw $exception;
			}
		}

		/**
		 * PDO query wrapper
		 * There are multiple configurations this method can be called with.
		 * http://php.net/manual/en/pdo.query.php
		 *
		 * public PDOStatement PDO::query ( string $statement )
		 * public PDOStatement PDO::query ( string $statement, int $PDO::FETCH_COLUMN, int $colno )
		 * public PDOStatement PDO::query ( string $statement, int $PDO::FETCH_CLASS, string $classname, array $ctorargs )
		 * public PDOStatement PDO::query ( string $statement, int $PDO::FETCH_INTO, object $object )
		 *
		 * @param $sql_statement
		 * @return PDOStatementWrapper
		 */
		public function query(string $sql_statement): PDOStatementWrapper {
			$timer = microtime(true);
			$statement = call_user_func_array([
				$this->pdo,
				"query"
			], func_get_args());

			//TODO: Add Log
			// if (defined('DEBUG') && DEBUG) {
			// 	$time = (microtime(true) - $timer) * 1000;
			// 	self::log_query($sql_statement, $time, debug_backtrace());
			// }

			return PDOStatementWrapper::create($statement, $sql_statement);
		}

		/**
		 * PDO wrapper for prepare
		 * http://php.net/manual/en/pdo.quote.php
		 */
		public function quote(string $string, int $parameter_type = PDO::PARAM_STR): string {
			return call_user_func_array([
				$this->pdo,
				"quote"
			], func_get_args());
		}

		/**
		 * PDO wrapper for prepare
		 * http://php.net/manual/en/pdo.prepare.php
		 */
		public function prepare(string $query, array $driver_options = []): bool|PDOStatementWrapper {
			$statement = call_user_func_array([
				$this->pdo,
				"prepare"
			], func_get_args());

			return PDOStatementWrapper::create($statement, $query);
		}

		/**
		 * PDO wrapper for setAttribute
		 * http://php.net/manual/en/pdo.setattribute.php
		 */
		public function setAttribute(int $attr, mixed $value): bool {
			return call_user_func_array([
				$this->pdo,
				"setAttribute"
			], func_get_args());
		}

		/**
		 * PDO wrapper for lastInsertId
		 * http://php.net/manual/en/pdo.lastinsertid.php
		 */
		public function lastInsertId(?string $name = null): string {
			return call_user_func_array([
				$this->pdo,
				"lastInsertId"
			], func_get_args());
		}

		/**
		 * PDO wrapper for errorInfo
		 * http://php.net/manual/en/pdo.errorinfo.php
		 */
		public function errorInfo(): array {
			return $this->pdo->errorInfo();
		}

		/**
		 * PDO wrapper for beginTransaction
		 * http://php.net/manual/en/pdo.begintransaction.php
		 */
		public function beginTransaction(): bool {
			return $this->pdo->beginTransaction();
		}

		/**
		 * PDO wrapper for rollBack
		 * http://php.net/manual/en/pdo.rollback.php
		 */
		public function rollBack(): bool {
			return $this->pdo->rollBack();
		}

		/**
		 * PDO wrapper for commit
		 * http://php.net/manual/en/pdo.commit.php
		 */
		public function commit(): bool {
			return $this->pdo->commit();
		}

		/**
		 * PDO wrapper for inTransaction
		 * http://php.net/manual/en/pdo.intransaction.php
		 */
		public function inTransaction(): bool {
			return $this->pdo->inTransaction();
		}

		/**
		 * Returns the serializable member variables
		 */
		public function __sleep(): array {
			return [];
		}

		/**
		 * Reestablish DB connections or any other reinitialization tasks that may have been lost during serialisation
		 */
		public function __wakeup() {
			$this->pdo = $this->createPDO();
		}

		/**
		 * Returns the number of rows in the full result set of the last query
		 */
		public function foundRows(): string {
			return $this->query("SELECT FOUND_ROWS()")->fetchColumn();
		}

		/**
		 * Sets a session variable for the current PDO session
		 */
		public function setSessionVariable($variable, $value) {
			if ($this->cached_vars[$variable] == $value) {
				return;
			}

			$statement = $this->prepare("SET SESSION `{$variable}` = :value");
			$statement->bindValue(':value', $value, is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT);
			$statement->execute();
			$statement = null;

			$this->cached_vars[$variable] = $value;
		}

		/**
		 * Returns the query log array
		 */
		public static function getQueryLog(): array {
			return self::$query_log;
		}

		/**
		 * Returns the query statistics
		 */
		public static function getQueryStatistics(): array {
			return self::$query_times;
		}

		// /**
		//  * Performs a crude version of PDO's injection to get a debuggable
		//  * copy-pasteable SQL query.
		//  *
		//  * @param $sql_string
		//  * @param array|null $params
		//  * @return mixed
		//  */
		// public static function get_debug_query_string($sql_string, array $params = null) {
		// 	if (!empty($params)) {
		// 		$indexed = $params == array_values($params);
		// 		$params = array_reverse($params);
		// 		foreach ($params as $key => $value) {
		// 			if (is_object($value)) {
		// 				if ($value instanceof DateTime) {
		// 					$value = $value->format('Y-m-d H:i:s');
		// 				} else {
		// 					continue;
		// 				}
		// 			} elseif (is_string($value)) {
		// 				$value = DataDB::escape($value);
		// 				$value = "'{$value}'";
		// 			} elseif ($value === null) {
		// 				$value = 'NULL';
		// 			} elseif (is_array($value)) {
		// 				$value = implode(',', $value);
		// 			}

		// 			if ($indexed) {
		// 				$sql_string = preg_replace('/\?/', $value, $sql_string, 1);
		// 			} else {
		// 				// Add leading colon if it was left out
		// 				if ($key[0] != ':') {
		// 					$key = ':' . $key;
		// 				}
		// 				$sql_string = str_replace($key, $value, $sql_string);
		// 			}
		// 		}
		// 	}

		// 	return $sql_string;
		// }

		// /**
		//  * Gets the current value for a given attribute
		//  *
		//  * @param int $attribute The attribute the get the value for
		//  *
		//  * @return int
		//  */
		// public function getAttribute(int $attribute): int {
		// 	return $this->pdo->getAttribute($attribute);
		// }

		// public static function disconnectConnections() {
		// 	if (self::$_instance !== null) {
		// 		self::$_instance->disconnect();
		// 		self::$_instance = null;
		// 	}

		// 	if (self::$_twitter_instance !== null) {
		// 		self::$_twitter_instance->disconnect();
		// 		self::$_twitter_instance = null;
		// 	}

		// 	if (self::$_data_warehouse_instance !== null) {
		// 		self::$_data_warehouse_instance->disconnect();
		// 		self::$_data_warehouse_instance = null;
		// 	}
		// }

		// /**
		//  * Returns true if pdo property is null
		//  *
		//  * @return bool
		//  */
		// public function is_pdo_null(): bool {
		// 	return $this->pdo === null;
		// }

		// /**
		//  * Return true if $_instance is null
		//  *
		//  * @return bool
		//  */
		// public function is_instance_null(): bool {
		// 	return self::$_instance === null;
		// }

		// private function disconnect() {
		// 	if ($this->pdo !== null) {
		// 		$this->pdo = null;
		// 		$this->hash = null;
		// 	}
		// }

		// public function is_redshift_connection(): bool {
		// 	return $this->is_redshift_connection;
		// }

		// /**
		//  * enables PDO::MYSQL_ATTR_USE_BUFFERED_QUERY on the connection
		//  */
		// public function enable_buffered_queries(): void {
		// 	$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		// }

		// /**
		//  * disables PDO::MYSQL_ATTR_USE_BUFFERED_QUERY on the connection
		//  */
		// public function disable_buffered_queries(): void {
		// 	$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
		// }

		protected function getPDO(): PDO {
			return $this->pdo;
		}
	}
