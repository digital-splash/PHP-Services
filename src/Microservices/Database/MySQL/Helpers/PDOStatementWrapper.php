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

		protected function __construct(PDOStatement|false &$statement, string $query = '') {
			$this->_statement = $statement;
			$this->_query = $query;
		}

		/**
		 * Factory method, creates PDOStatementWrapper or returns false if statement is false
		 */
		public static function create(PDOStatement|false &$statement, $query = '') {
			if (!$statement) {
				return false;
			}
			return new PDOStatementWrapper($statement, $query);
		}

		// /**
		//  * Creates the comma separated parameterized values for using in SQL INSERT statements.
		//  * e.g. INSERT INTO `table` (column1, column2) VALUES {$RETURN_VALUE}
		//  *
		//  * @param array $rows The rows that you will be inserting into the database.
		//  * @param array $prefixes The names of the parameters for each of the columns.
		//  * @return string ready-to-insert string for VALUES.
		//  */
		// public static function getParameterizedInserts(array $rows, array $prefixes): string {
		// 	$inserts = [];
		// 	if (count($prefixes) === 0) {
		// 		return '';
		// 	}
		// 	for($i = 1, $rowCount = count($rows); $i <= $rowCount; $i++) {
		// 		$single_insert = [];
		// 		foreach($prefixes as $prefix) {
		// 			$single_insert[] = ":{$prefix}{$i}";
		// 		}
		// 		$single_insert_str = implode(',', $single_insert);
		// 		$inserts[] = "({$single_insert_str})";
		// 	}
		// 	return implode(',', $inserts);
		// }

		// /**
		//  * @param string $key_column
		//  * @param string $key_prefix
		//  * @param string $value_prefix
		//  * @param int $row_count
		//  * @return string
		//  */
		// public static function getParameterizedUpdateCase(string $key_column, string $key_prefix, string $value_prefix, int $row_count): string {
		// 	$case_whens = [];
		// 	if (empty($value_prefix) || empty($key_prefix) || empty($key_column)) {
		// 		return '';
		// 	}
		// 	for($i = 1; $i <= $row_count; $i++) {
		// 		$case_whens[] = "when `{$key_column}` = :{$key_prefix}{$i} then :{$value_prefix}{$i}";
		// 	}
		// 	return implode("\n", $case_whens);
		// }

		// /**
		//  * Returns the parameterized list and keys
		//  *
		//  * @param array $values
		//  * @param string $prefix
		//  * @param int $pdo_type
		//  * @param string $separator
		//  * @return array
		//  */
		// public static function binds(array $values, string $prefix, int $pdo_type = PDO::PARAM_STR, string $separator = ','): array {
		// 	$binds = self::parameterizeValues($values, $prefix, $pdo_type);
		// 	return [
		// 		$binds,
		// 		self::getParameterizedList($binds, $separator)
		// 	];
		// }

		// /**
		//  * Generates an array of parameters to a format for bindValues().
		//  *
		//  * Mostly used for parameterizing IN clauses
		//  * Example:
		//  * parameterizeValues([2017, 9, 18], 'num', PDO::PARAM_INT)
		//  * [
		//  *   ':num1' => ['value' => 2017, 'type' => PDO::PARAM_INT],
		//  *   ':num2' => ['value' => 9, 'type' => PDO::PARAM_INT],
		//  *   ':num3' => ['value' => 18, 'type' => PDO::PARAM_INT]
		//  * ]
		//  *
		//  * @param array $values
		//  * @param string $prefix Base name for parameters
		//  * @param mixed $pdo_type The PDO type constant
		//  * @return array[]
		//  */
		// public static function parameterizeValues(array $values, $prefix, $pdo_type = PDO::PARAM_STR) {
		// 	$params = [];
		// 	$i = 1;
		// 	foreach ($values as $value) {
		// 		$params[":" . $prefix . $i] = ['value' => $value, 'type' => $pdo_type];
		// 		$i++;
		// 	}
		// 	return $params;
		// }

		// /**
		//  * Creates a list from `parameterizeValues` that can be used in a SQL `IN()` statement.
		//  *
		//  * @author Tanner Mckenney
		//  * @param  array   $paramValues Returned from parameterizedValues or another key-based bind list.
		//  * @param  string  $separator a separator for the list, defaults to ','.
		//  * @return string  ready-to-insert string for IN().
		//  *
		//  */
		// public static function getParameterizedList(array $paramValues, $separator = ",") {
		// 	return implode($separator, array_keys($paramValues));
		// }

		// /**
		//  * Clear reference
		//  */
		// public function __destruct() {
		// 	foreach ($this->bound_values as $key => $val) {
		// 		$this->bound_values[$key] = null;
		// 	}

		// 	$this->_statement = null;
		// }

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

		// /**
		//  * Additional method for returning the string presentation of the original query
		//  *
		//  * @return string       Query string representation
		//  */
		// public function get_query($bind_params = false) {
		// 	if ($bind_params) {
		// 		return PDODataDB::get_debug_query_string($this->_query, $this->bound_values);
		// 	}
		// 	return $this->_query;
		// }

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
		public function fetch($fetch_style = PDO::FETCH_BOTH): mixed {
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
		public function fetchColumn(int $column_number = 0): mixed {
			return call_user_func_array(
				[
					$this->_statement,
					"fetchColumn"
				], func_get_args());
		}

		// /**
		//  * Wrapper for PDOStatement::fetchAll
		//  *
		//  * http://php.net/manual/en/pdostatement.fetchall.php
		//  *
		//  * @param int $fetch_style
		//  * @param mixed $fetch_argument
		//  * @param array $ctor_args
		//  * @return mixed
		//  */
		// public function fetchAll($fetch_style = PDO::FETCH_BOTH, $fetch_argument = null, $ctor_args = []) {
		// 	$args = func_get_args();
		// 	return call_user_func_array(array($this->_statement, "fetchAll"), $args);
		// }

		// /**
		//  * Wrapper for PDOStatement::nextRowset
		//  *
		//  * http://php.net/manual/en/pdostatement.nextrowset.php
		//  *
		//  * @return mixed
		//  */
		// public function nextRowset() {
		// 	return $this->_statement->nextRowset();
		// }

		// /**
		//  * Wrapper for PDOStatement::rowCount
		//  *
		//  * http://php.net/manual/en/pdostatement.rowcount.php
		//  *
		//  * @return int
		//  */
		// public function rowCount() {
		// 	return $this->_statement->rowCount();
		// }

		// /**
		//  * Wrapper for PDOStatement::columnCount
		//  *
		//  * http://php.net/manual/en/pdostatement.columncount.php
		//  *
		//  * @return mixed
		//  */
		// public function columnCount() {
		// 	return $this->_statement->columnCount();
		// }

		// /**
		//  * Wrapper for PDOStatement::closeCursor
		//  *
		//  * http://php.net/manual/en/pdostatement.closecursor.php
		//  *
		//  * @return mixed
		//  */
		// public function closeCursor() {
		// 	return $this->_statement->closeCursor();
		// }

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
	}
