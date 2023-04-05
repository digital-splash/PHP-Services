<?php
	namespace DigitalSplash\Database\MySQL\Helpers;

	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use PDO;

	class QueryBuilder {
		const SQL = 'sql';
		const BINDS = 'binds';

		protected string $table;
		protected string $database;

		public function __construct(
			string $database,
			string $table
		) {
			$this->database = $database;
			$this->table = $table;
		}

		public function getDatabase(): string {
			return $this->database;
		}

		public function getTable(): string {
			return $this->table;
		}

		public function insert(
			array $data = [],
		): array {
			if (Helper::ArrayNullOrEmpty($data)) {
				throw new NotEmptyParamException('data');
			}

			$columns = [];
			$binds = [];

			foreach ($data AS $column => $value) {
				if (!in_array($column, $columns)) {
					$columns[] = "`{$column}`";
				}
				$bind_key = ':' . $column;

				$binds[$bind_key] = [
					'value' => $value,
					'type' => self::GetPDOTypeFromValue($value)
				];
			}
			$columnsStr = Helper::ImplodeArrToStr($columns, ', ');
			$bindValues = Helper::ImplodeArrToStr(array_keys($binds), ', ');

			$sql = "INSERT INTO {$this->database}.{$this->table} ($columnsStr) VALUES ($bindValues)";

			return [
				self::SQL => $sql,
				self::BINDS => $binds
			];
		}

		public function update(
			array $data = [],
			array $where = []
		): array {
			if (Helper::ArrayNullOrEmpty($data)) {
				throw new NotEmptyParamException('data');
			}

			$columns = [];
			$binds = [];

			foreach ($data AS $column => $value) {
				if (!in_array($column, $columns)) {
					$columns[] = "`{$column}`";
				}
				$bind_key = ':' . $column;

				$binds[$bind_key] = [
					'value' => $value,
					'type' => self::GetPDOTypeFromValue($value)
				];
			}
			$columnsStr = Helper::ImplodeArrToStr($columns, ', ');
			$bindValues = Helper::ImplodeArrToStr(array_keys($binds), ', ');

			$sql = "UPDATE {$this->database}.{$this->table} SET ($columnsStr) VALUES ($bindValues)";

			if (!Helper::ArrayNullOrEmpty($where)) {
				$whereStr = '';
				foreach ($where AS $column => $value) {
					$whereStr .= "`{$column}` = :{$column} AND ";
					$bind_key = ':' . $column;

					$binds[$bind_key] = [
						'value' => $value,
						'type' => self::GetPDOTypeFromValue($value)
					];
				}
				$whereStr = rtrim($whereStr, ' AND ');
				$sql .= " WHERE $whereStr";
			}

			return [
				self::SQL => $sql,
				self::BINDS => $binds
			];
		}

		public function delete(
			array $where = []
		): array {
			$binds = [];
			$sql = "DELETE FROM {$this->database}.{$this->table}";

			if (!Helper::ArrayNullOrEmpty($where)) {
				$whereStr = '';
				foreach ($where AS $column => $value) {
					$whereStr .= "`{$column}` = :{$column} AND ";
					$bind_key = ':' . $column;

					$binds[$bind_key] = [
						'value' => $value,
						'type' => self::GetPDOTypeFromValue($value)
					];
				}
				$whereStr = rtrim($whereStr, ' AND ');
				$sql .= " WHERE $whereStr";
			}

			return [
				self::SQL => $sql,
				self::BINDS => $binds
			];
		}

		public function select(
			array $columns = [],
			array $where = [],
			array $join,
			string $orderBy,
			string $orderType,
			int $limit,
			int $offset
		): array {
			$columnsStr = '*';
			if (!Helper::ArrayNullOrEmpty($columns)) {
				$columnsStr = Helper::ImplodeArrToStr($columns, ',');
			}

			$sql = "SELECT $columnsStr FROM {$this->database}.{$this->table}";

			if (!Helper::ArrayNullOrEmpty($join)) {
				foreach ($join AS $joinTable => $joinData) {
					$sql .= " {$joinData['type']} JOIN {$joinTable} ON {$joinData['on']}";
				}
			}

			if (!Helper::ArrayNullOrEmpty($where)) {
				$whereStr = '';
				foreach ($where AS $column => $value) {
					$whereStr .= "`{$column}` = :{$column} AND ";
					$bind_key = ':' . $column;

					$binds[$bind_key] = [
						'value' => $value,
						'type' => self::GetPDOTypeFromValue($value)
					];
				}
				$whereStr = rtrim($whereStr, ' AND ');
				$sql .= " WHERE $whereStr";
			}

			if (!empty($orderBy)) {
				$sql .= " ORDER BY $orderBy $orderType";
			}

			if (!empty($limit)) {
				$sql .= " LIMIT $limit";
			}

			if (!empty($offset)) {
				$sql .= " OFFSET $offset";
			}

			return [
				self::SQL => $sql,
				self::BINDS => $binds
			];
		}



		// public function update(
		// 	string $table,
		// 	array $data,
		// 	array $where,
		// 	DatabaseCredentials $db
		// ): string {
		// 	$columns = array_keys($data);
		// 	$values = array_values($data);
		// 	$placeholders = implode(', ', $data);
		// 	$columns = implode(", ", $columns);
		// 	$sql = "UPDATE $table SET ($columns) VALUES ($placeholders) WHERE $where";
		// 	return $sql;
		// }

		// public  function delete(
		// 	string $table,
		// 	array $where,
		// 	DatabaseCredentials $db
		// ): string {
		// 	$sql = "DELETE FROM $table WHERE $where";
		// 	return $sql;
		// }

		// public static function select(
		//     string $table,
		//     array $columns,
		//     array $where,
		//     DatabaseCredentials $db
		// ): string {
		//     $columns = implode(", ", $columns);
		//     $sql = "SELECT $columns FROM $table WHERE $where";
		//     return $sql;
		// }



		// public static function selectAll(
		//     string $table,
		//     DatabaseCredentials $db
		// ): string {
		//     $sql = "SELECT * FROM $table";
		//     return $sql;
		// }

		// public static function selectAllWhere(
		//     string $table,
		//     array $where,
		//     DatabaseCredentials $db
		// ): string {
		//     $sql = "SELECT * FROM $table WHERE " . implode(" AND ", $where);
		//     return $sql;
		// }

		// public static function selectAllOrderBy(
		//     string $table,
		//     DatabaseCredentials $db,
		//     string $orderBy
		// ): string {
		//     $sql = "SELECT * FROM $table ORDER BY $orderBy";
		//     return $sql;
		// }

		// public static function selectAllWhereOrderBy(
		//     string $table,
		//     array $where,
		//     string $orderBy
		// ): string {
		//     $sql = "SELECT * FROM $table WHERE $where ORDER BY $orderBy";
		//     return $sql;
		// }


		// public static function selectAllJoin(
		//     string $table,
		//     array $columns,
		//     array $join,
		// ): string {
		//     $columns = implode(", ", $columns);
		//     $sql = "SELECT $columns FROM $table";
		//     foreach($join as $j){
		//         $sql .= " JOIN $j";
		//     }
		//     return $sql;
		// }

		// public static function selectAllJoinWhere(
		//     string $table,
		//     array $columns,
		//     array $join,
		//     array $where,
		//     DatabaseCredentials $db
		// ): string {
		//     $columns = implode(", ", $columns);
		//     $sql = "SELECT $columns FROM $table";
		//     foreach($join as $j){
		//         $sql .= " JOIN $j";
		//     }
		//     $sql .= " WHERE $where";
		//     return $sql;
		// }


		// public static function selectAllJoinWhereOrderBy(
		//     string $table,
		//     array $columns,
		//     array $join,
		//     array $where,
		//     array $orderBy,
		//     DatabaseCredentials $db
		// ): string {
		//     $columns = implode(", ", $columns);
		//     $sql = "SELECT $columns FROM $table";
		//     foreach($join as $j){
		//         $sql .= " JOIN $j";
		//     }
		//     $sql .= " WHERE $where";
		//     $sql .= " ORDER BY $orderBy";
		//     return $sql;
		// }

		// public static function selectAllJoinOrderBy(
		//     string $table,
		//     array $columns,
		//     array $join,
		//     array $orderBy,
		//     DatabaseCredentials $db
		// ): string {
		//     $columns = implode(", ", $columns);
		//     $sql = "SELECT $columns FROM $table";
		//     foreach($join as $j){
		//         $sql .= " JOIN $j";
		//     }
		//     $sql .= " ORDER BY $orderBy";
		//     return $sql;
		// }



		// public function select(
		// 	string $table,
		// 	array $columns = [],
		// 	array $where = [],
		// 	array $join = [],
		// 	string $orderBy = ""
		// ): string {
		// 	$sql = "SELECT ";
		// 	if (empty($columns)) {
		// 		$sql .= "*";
		// 	} else {
		// 		$sql .= implode(", ", $columns);
		// 	}

		// 	$sql .= " FROM $table";

		// 	foreach ($join as $j) {
		// 		$sql .= " JOIN $j";
		// 	}

		// 	if (!empty($where)) {
		// 		$whereClause = implode(" AND ", $where);
		// 		$sql .= " WHERE $whereClause";
		// 	}

		// 	if (!empty($orderBy)) {
		// 		$sql .= " ORDER BY $orderBy";
		// 	}

		// 	return $sql;
		// }

		public static function GetPDOTypeFromValue(
			mixed $value
		): int {
			$type = PDO::PARAM_STR;

			$valueType = gettype($value);
			if ($valueType === 'integer' || $valueType === 'double') {
				$type = PDO::PARAM_INT;
			}

			return $type;
		}

	}