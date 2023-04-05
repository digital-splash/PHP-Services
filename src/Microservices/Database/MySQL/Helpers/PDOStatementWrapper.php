<?php
	namespace DigitalSplash\Database\MySQL\Helpers;

	use DigitalSplash\Exceptions\InvalidParamException;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use PDO;
	use PDOStatement;

	class PDOStatementWrapper {
		protected PDOStatement|false $_statement = false;
		protected string $_query = '';
		protected bool $__debug = false;
		protected array $bound_values = [];

		protected function __construct(
			PDOStatement|false &$statement,
			string $query = ''
		) {
			$this->_statement = $statement;
			$this->_query = $query;
		}

		/**
		 * Factory method, creates PDOStatementWrapper or returns false if statement is false
		 */
		public static function create(
			PDOStatement|false &$statement,
			$query = ''
		) {
			if (!$statement) {
				return false;
			}
			return new PDOStatementWrapper($statement, $query);
		}

		/**
		 * Clear reference
		 */
		public function __destruct() {
			foreach ($this->bound_values AS $key => $val) {
				$this->bound_values[$key] = null;
			}
			$this->_statement = null;
		}

		/**
		 * Wrapper for PDOStatement::execute
		 * http://php.net/manual/en/pdostatement.execute.php
		 */
		public function execute(
			?array $params = null
		): bool {
			if (!$this->_statement) {
				throw new InvalidParamException('statement');
			}

			return call_user_func_array([
				$this->_statement,
				"execute"
			], func_get_args());
		}

		/**
		 * Additional method for returning the string presentation of the original query
		 */
		public function get_query(
			bool $bind_params = false
		): string {
			if ($bind_params) {
				return PDODataDB::getDebugQueryString($this->_query, $this->bound_values);
			}
			return $this->_query;
		}

		/**
		 * Wrapper for PDOStatement::setFetchMode
		 * http://php.net/manual/en/pdostatement.setFetchMode.php
		 */
		public function setFetchMode(): bool {
			return call_user_func_array([
				$this->_statement,
				"setFetchMode"
			], func_get_args());
		}

		/**
		 * Wrapper for PDOStatement::fetch
		 * http://php.net/manual/en/pdostatement.fetch.php
		 * Note: returns false if no rows found
		 */
		public function fetch(
			int $fetch_style = PDO::FETCH_BOTH
		): mixed {
			return call_user_func_array(
				[
					$this->_statement,
					"fetch"
				], func_get_args());
		}

		/**
		 * Wrapper for PDOStatement::fetchColumn
		 * http://php.net/manual/en/pdostatement.fetchcolumn.php
		 */
		public function fetchColumn(
			int $column_number = 0
		): mixed {
			return call_user_func_array(
				[
					$this->_statement,
					"fetchColumn"
				], func_get_args());
		}

		/**
		 * Wrapper for PDOStatement::fetchAll
		 * http://php.net/manual/en/pdostatement.fetchall.php
		 */
		public function fetchAll(
			int $fetch_style = PDO::FETCH_BOTH,
			mixed $fetch_argument = null,
			array $ctor_args = []
		): array|false {
			return call_user_func_array([
				$this->_statement,
				"fetchAll"
			], func_get_args());
		}

		/**
		 * Wrapper for PDOStatement::nextRowset
		 * http://php.net/manual/en/pdostatement.nextrowset.php
		 */
		public function nextRowset(): bool {
			return $this->_statement->nextRowset();
		}

		/**
		 * Wrapper for PDOStatement::rowCount
		 * http://php.net/manual/en/pdostatement.rowcount.php
		 */
		public function rowCount(): int {
			return $this->_statement->rowCount();
		}

		/**
		 * Wrapper for PDOStatement::columnCount
		 * http://php.net/manual/en/pdostatement.columncount.php
		 */
		public function columnCount(): int {
			return $this->_statement->columnCount();
		}

		/**
		 * Wrapper for PDOStatement::closeCursor
		 * http://php.net/manual/en/pdostatement.closecursor.php
		 */
		public function closeCursor(): bool {
			return $this->_statement->closeCursor();
		}

		/**
		 * Wrapper for PDOStatement::errorInfo
		 * http://php.net/manual/en/pdostatement.errorinfo.php
		 */
		public function errorInfo(): array {
			return $this->_statement->errorInfo();
		}

		/**
		 * Wrapper for PDOStatement::debugDumpParams
		 * http://php.net/manual/en/pdostatement.debugdumpparams.php
		 */
		public function debugDumpParams(): ?bool {
			return $this->_statement->debugDumpParams();
		}

		/**
		 * Wrapper for PDOStatement::bindParam
		 * http://php.net/manual/en/pdostatement.bindparam.php
		 */
		public function bindParam(
			int|string $parameter,
			mixed &$variable,
			int $data_type = PDO::PARAM_STR,
			int $length = null,
			mixed $driver_options = null
		): bool {
			$args = func_get_args();
			$args[1] = &$variable;

			if ($this->__debug) {
				$this->bound_values[$parameter] = &$variable;
			}

			return call_user_func_array([
				$this->_statement,
				"bindParam"
			], $args);
		}

		/**
		 * Wrapper for PDOStatement::bindValue
		 * http://php.net/manual/en/pdostatement.bindvalue.php
		 */
		public function bindValue(
			int|string $parameter,
			mixed $value,
			int $data_type = PDO::PARAM_STR
		): bool {
			$args = func_get_args();

			if ($this->__debug) {
				$this->bound_values[$parameter] = $value;
			}

			// Do manual casting since PDO::bindValue does not necessarily perform it
			if ($data_type === PDO::PARAM_INT) {
				$args[1] = Helper::ConvertToInt($value);
			}

			return call_user_func_array([
				$this->_statement,
				"bindValue"
			], $args);
		}

		/**
		 * Binds multiple values from an associative array that must be in format:
		 * [':key' => ['value' => value, 'type' => PDO::PARAM_?]]
		 */
		public function bindValues(
			array $values,
			bool $allow_nulls = false
		): void {
			foreach ($values as $key => $value) {
				if (!$allow_nulls && !isset($value['value'])) {
					throw new NotEmptyParamException($key);
				}

				$this->bindValue($key, $value['value'], $value['type'] ?? PDO::PARAM_STR);
			}
		}

		/**
		 * Sets debug on or off for this query
		 */
		public function set_debug(
			bool $debug
		): void {
			$this->__debug = $debug;
		}


		// /**
		//  * Creates the comma separated parameterized values for using in SQL INSERT statements.
		//  * e.g. INSERT INTO `table` (column1, column2) VALUES {$RETURN_VALUE}
		//  */
		// public static function getParameterizedInserts(
		// 	array $rows,
		// 	array $prefixes
		// ): string {
		// 	$inserts = [];
		// 	if (count($prefixes) === 0) {
		// 		return '';
		// 	}
		// 	for ($i = 1, $rowCount = count($rows); $i <= $rowCount; $i++) {
		// 		$single_insert = [];
		// 		foreach($prefixes AS $prefix) {
		// 			$single_insert[] = ":{$prefix}_{$i}";
		// 		}
		// 		$single_insert_str = implode(',', $single_insert);
		// 		$inserts[] = "({$single_insert_str})";
		// 	}
		// 	return implode(',', $inserts);
		// }

		// public static function getParameterizedUpdateCase(
		// 	string $key_column,
		// 	string $key_prefix,
		// 	string $value_prefix,
		// 	int $row_count
		// ): string {
		// 	$case_whens = [];
		// 	if (empty($value_prefix) || empty($key_prefix) || empty($key_column)) {
		// 		return '';
		// 	}
		// 	for($i = 1; $i <= $row_count; $i++) {
		// 		$case_whens[] = "WHEN `{$key_column}` = :{$key_prefix}{$i} THEN :{$value_prefix}_{$i}";
		// 	}
		// 	return implode("\n", $case_whens);
		// }

		// /**
		//  * Returns the parameterized list and keys
		//  */
		// public static function binds(
		// 	array $values,
		// 	string $prefix,
		// 	int $pdo_type = PDO::PARAM_STR,
		// 	string $separator = ','
		// ): array {
		// 	$binds = self::parameterizeValues($values, $prefix, $pdo_type);
		// 	return [
		// 		$binds,
		// 		self::getParameterizedList($binds, $separator)
		// 	];
		// }

		// /**
		//  * Generates an array of parameters to a format for bindValues()
		//  */
		// public static function parameterizeValues(
		// 	array $values,
		// 	string $prefix,
		// 	int $pdo_type = PDO::PARAM_STR
		// ): array {
		// 	$params = [];
		// 	$i = 1;
		// 	foreach ($values AS $value) {
		// 		$bindKey = ":{$prefix}_{$i}";
		// 		$params[$bindKey] = [
		// 			'value' => $value,
		// 			'type' => $pdo_type
		// 		];
		// 		$i++;
		// 	}
		// 	return $params;
		// }

		// /**
		//  * Creates a list from `parameterizeValues` that can be used in a SQL `IN()` statement.
		//  */
		// public static function getParameterizedList(
		// 	array $paramValues,
		// 	string $separator = ","
		// ): string {
		// 	return Helper::ImplodeArrToStr(array_keys($paramValues), $separator);
		// }
	}
