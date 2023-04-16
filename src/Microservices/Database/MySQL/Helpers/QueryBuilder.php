<?php
	namespace DigitalSplash\Database\MySQL\Helpers;

	use DigitalSplash\Classes\Database\Order;
	use DigitalSplash\Database\MySQL\Models\Binds;
	use DigitalSplash\Database\MySQL\Models\Data;
	use DigitalSplash\Database\MySQL\Models\Group;
	use DigitalSplash\Database\MySQL\Models\Having;
	use DigitalSplash\Database\MySQL\Models\Join;
	use DigitalSplash\Database\MySQL\Models\Limit;
	use DigitalSplash\Database\MySQL\Models\Offset;
	use DigitalSplash\Database\MySQL\Models\Sql;
	use DigitalSplash\Database\MySQL\Models\Where;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use PDO;

	class QueryBuilder {
		const SQL = 'sql';
		const BINDS = 'binds';

		protected string $database;
		protected string $table;
		public Sql $sql;
		public Binds $binds;
		public Data $data;
		public Where $where;
		public Having $having;
		public Join $join;
		public Group $group;
		public Order $order;
		public Limit $limit;
		public Offset $offset;

		public function __construct(
			string $database,
			string $table
		) {
			if (Helper::StringNullOrEmpty($database)) {
				throw new NotEmptyParamException('database');
			}

			if (Helper::StringNullOrEmpty($table)) {
				throw new NotEmptyParamException('table');
			}

			$this->database = $database;
			$this->table = $table;
			$this->sql = new Sql();
			$this->binds = new Binds();
			$this->data = new Data();

			$this->where = new Where();
			$this->having = new Having();
			$this->join = new Join();
			$this->group = new Group();
			$this->order = new Order();
			$this->limit = new Limit();
			$this->offset = new Offset();
		}

		public static function GetPDOTypeFromValue($value): int {
			$type = PDO::PARAM_STR;

			$valueType = gettype($value);
			if ($valueType === 'integer' || $valueType === 'double') {
				$type = PDO::PARAM_INT;
			}

			return $type;
		}

		public function getDatabase(): string {
			return $this->database;
		}

		public function getTable(): string {
			return $this->table;
		}

		// public function insert(): array {
		// 	if (Helper::ArrayNullOrEmpty($this->data)) {
		// 		throw new NotEmptyParamException('data');
		// 	}

		// 	$columns = [];
		// 	$this->clearBinds();
		// 	$rows= [];
		// 	$i = 1;
		// 	foreach ($this->data as $row) {
		// 		$rowColumns = [];
		// 		foreach ($row as $column => $value) {
		// 			if (!in_array("`{$column}`", $columns)) {
		// 				$columns[] = "`{$column}`";
		// 			}
		// 			$bind_key = ":{$column}_{$i}";
		// 			$bind_arr = [
		// 				'value' => $value,
		// 				'type' => self::GetPDOTypeFromValue($value)
		// 			];
		// 			$this->appendToBind($bind_key, $bind_arr);
		// 			$rowColumns[] = $bind_key;
		// 		}
		// 		$rows[] = '(' . implode(', ', $rowColumns) . ')';
		// 		$i++;
		// 	}

		// 	$columnsStr = Helper::ImplodeArrToStr($columns, ', ');
		// 	$rowsStr = implode(', ', $rows);

		// 	$sql = "INSERT INTO `{$this->database}`.`{$this->table}` ($columnsStr) VALUES $rowsStr";
		// 	$this->setSql($sql);

		// 	return [
		// 		self::SQL => $this->getSql(),
		// 		self::BINDS => $this->getBinds()
		// 	];
		// }


		// public function update(): array {
		// 	if (Helper::ArrayNullOrEmpty($this->data)) {
		// 		throw new NotEmptyParamException('data');
		// 	}

		// 	$columns = [];
		// 	$binds = [];
		// 	$columnsStr = '';
		// 	foreach ($this->data AS $column => $value) {
		// 		if (!in_array($column, $columns)) {
		// 			$columns[] = "`{$column}`";
		// 		}
		// 		$bind_key = ':' . $column;

		// 		$binds[$bind_key] = [
		// 			'value' => $value,
		// 			'type' => self::GetPDOTypeFromValue($value)
		// 		];

		// 		$columnsStr .= "`{$column}` = :{$column}, ";

		// 	}

		// 	$this->getWhereStatement();
		// 	$binds = array_merge($binds, $this->_binds);

		// 	$columnsStr = rtrim($columnsStr, ', ');
		// 	$sql = "UPDATE {$this->database}.{$this->table} SET $columnsStr" . $this->where;

		// 	return [
		// 		self::SQL => $sql,
		// 		self::BINDS => $binds
		// 	];
		// }

		// public function delete(): array {

		// 	if (Helper::ArrayNullOrEmpty($this->whereData)) {
		// 		throw new NotEmptyParamException('whereData');
		// 	}

		// 	$this->getWhereStatement();

		// 	$sql = "DELETE FROM {$this->database}.{$this->table}" . $this->where;

		// 	return [
		// 		self::SQL => $sql,
		// 		self::BINDS => $this->_binds
		// 	];
		// }

		// public function select(): array {

		// 	if (Helper::ArrayNullOrEmpty($this->data)) {
		// 		throw new NotEmptyParamException('data');
		// 	}

		// 	$columnsStr = '*';

		// 	if (!Helper::ArrayNullOrEmpty($this->data)) {
		// 		$columnsStr = Helper::ImplodeArrToStr($this->data, ', ');
		// 	}

		// 	$this->getWhereStatement();

		// 	$this->getJoinStatement();

		// 	$this->getLimitStatement();

		// 	$this->getOrderByStatement();

		// 	$this->getGroupByStatement();

		// 	$this->getHavingStatement();

		// 	$this->getSuffixStatement();



		// 	$sql = "SELECT $columnsStr FROM {$this->database}.{$this->table}"
		// 			. $this->join   . $this->where
		// 			. $this->group  . $this->having
		// 			. $this->order  . $this->limit
		// 			. $this->suffix;

		// 	return [
		// 		self::SQL => $sql,
		// 		self::BINDS => $this->_binds
		// 	];
		// }

	}
