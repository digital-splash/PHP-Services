<?php
	namespace DigitalSplash\Database\MySQL\Helpers;

	use DigitalSplash\Database\MySQL\Models\Binds;
	use DigitalSplash\Database\MySQL\Models\Data;
	use DigitalSplash\Database\MySQL\Models\Group;
	use DigitalSplash\Database\MySQL\Models\Having;
	use DigitalSplash\Database\MySQL\Models\Join;
	use DigitalSplash\Database\MySQL\Models\Limit;
	use DigitalSplash\Database\MySQL\Models\Offset;
	use DigitalSplash\Database\MySQL\Models\Order;
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

		public function insert(): array {
			if (Helper::ArrayNullOrEmpty($this->data->getData())) {
				throw new NotEmptyParamException('data');
			}

			$columns = [];
			$binds = [];
			$r = 1;
			foreach ($this->data->getData() AS $rows) {
				$row_binds = [];

				foreach ($rows AS $column => $value) {
					if (!in_array($column, $columns)) {
						$columns[] = $column;
					}

					$bind_key = ':' . $column . "_" . $r;
					$this->binds->appendToBinds($bind_key, [
						'value' => $value,
						'type' => self::GetPDOTypeFromValue($value)
					]);

					$row_binds[] = $bind_key;
				}

				$binds[] = "(" . Helper::ImplodeArrToStr($row_binds, ', ') . ")";
				$r++;
			}

			$columnsStr = '`' . Helper::ImplodeArrToStr($columns, '`, `') . '`';
			$bindsStr = Helper::ImplodeArrToStr($binds, ', ');

			$this->sql->setValue("INSERT INTO `{$this->database}`.`{$this->table}` ({$columnsStr}) VALUES {$bindsStr}");
			$this->sql->generateStringStatement();
			return [
				self::SQL => $this->sql->getFinalString(),
				self::BINDS => $this->binds->getBinds()
			];
		}

		public function update(): array {
			if (Helper::ArrayNullOrEmpty($this->data->getData())) {
				throw new NotEmptyParamException('data');
			}

			$singleUpdate = count($this->data->getData()) === 1;
			if ($singleUpdate) {
				return $this->update_single();
			}
			return $this->update_bulk();
		}

		private function update_single(): array {
			$updates = [];
			$r = 1;
			foreach ($this->data->getData()[0] AS [
				'filter' => $filter,
				'values' => $values
			]) {
				foreach ($filter AS $column => $value) {
					$this->where->appendToArray($column, $value);
				}

				foreach ($values AS $column => $value) {
					$bind_key = ':' . $column . "_" . $r;
					$this->binds->appendToBinds($bind_key, [
						'value' => $value,
						'type' => self::GetPDOTypeFromValue($value)
					]);
					$updates[] = "`{$column}` = $bind_key";
				}
			}
			$updateStr = Helper::ImplodeArrToStr($updates, ', ');

			$this->where->generateStringStatement();

			$sql = Helper::ImplodeArrToStr([
				"UPDATE `{$this->database}`.`{$this->table}` SET {$updateStr}",
				$this->where->getFinalString()
			], ' ');

			$this->sql->setValue($sql);
			$this->sql->generateStringStatement();
			$this->binds->appendArrayToBinds($this->where->binds->getBinds());

			return [
				self::SQL => $this->sql->getFinalString(),
				self::BINDS => $this->binds->getBinds()
			];
		}

		private function update_bulk(): array {
			// $updates = [];
			// $cases = [];
			// $r = 1;
			// foreach ($this->data->getData() AS $row) {
			// 	foreach ($row AS [
			// 		'filter' => $filter,
			// 		'values' => $values
			// 	]) {
			// 		//TODO: Implement IN () in WHERE statment cz now we will have `id` = 1 AND `id` = 2 instead of `id` IN (1, 2)
			// 		foreach ($filter AS $column => $value) {
			// 			$this->where->appendToArray($column, $value);
			// 		}

			// 		foreach ($values AS $column => $value) {
			// 			if (!array_key_exists($column, $cases)) {
			// 				$cases[$column] = [];
			// 			}

			// 			$caseWhere = [];
			// 			foreach ($filter AS $column => $value) {
			// 				$caseWhereBindKey = ':' $column
			// 				$caseWhere[] =
			// 			}

			// 		}

			// // 		$bind_key = ':' . $column . "_" . $r;
			// // 		$this->binds->appendToBinds($bind_key, [
			// // 			'value' => $value,
			// // 			'type' => self::GetPDOTypeFromValue($value)
			// // 		]);

			// // 		$updates[] = "`{$column}` = $bind_key";
			// 	}

			// 	$r++;
			// }
			// // $updateStr = ''; //Helper::ImplodeArrToStr($updates, ', ');

			// // $this->where->generateStringStatement();

			// // $sql = Helper::ImplodeArrToStr([
			// // 	"UPDATE `{$this->database}`.`{$this->table}` SET {$updateStr}",
			// // 	$this->where->getFinalString()
			// // ], ' ');

			// // $this->sql->setValue($sql);
			// // $this->sql->generateStringStatement();
			// // $this->binds->setBinds($this->where->binds->getBinds());

			return [
				self::SQL => $this->sql->getFinalString(),
				self::BINDS => $this->binds->getBinds()
			];
		}

		public function delete(): array {
			$this->join->generateStringStatement();
			$this->where->generateStringStatement();

			$sql = Helper::ImplodeArrToStr([
				"DELETE FROM `{$this->database}`.`{$this->table}`",
				$this->join->getFinalString(),
				$this->where->getFinalString()
			], ' ');

			$this->sql->setValue($sql);
			$this->sql->generateStringStatement();
			$this->binds->setBinds($this->where->binds->getBinds());

			return [
				self::SQL => $this->sql->getFinalString(),
				self::BINDS => $this->binds->getBinds()
			];
		}

		public function select(): array {
			$columnsStr = Helper::ImplodeArrToStr($this->data->getData(), '`, `');
			if (Helper::StringNullOrEmpty($columnsStr)) {
				$columnsStr = '*';
			} else {
				$columnsStr = '`' . $columnsStr . '`';
			}

			$this->where->generateStringStatement();
			$this->join->generateStringStatement();
			$this->group->generateStringStatement();
			$this->having->generateStringStatement();
			$this->order->generateStringStatement();
			$this->limit->generateStringStatement();
			$this->offset->generateStringStatement();

			$this->binds->setBinds(array_merge(
				$this->where->binds->getBinds(),
				$this->having->binds->getBinds()
			));

			$sql = Helper::ImplodeArrToStr([
				"SELECT $columnsStr FROM `{$this->database}`.`{$this->table}`",
				$this->join->getFinalString(),
				$this->where->getFinalString(),
				$this->group->getFinalString(),
				$this->having->getFinalString(),
				$this->order->getFinalString(),
				$this->limit->getFinalString(),
				$this->offset->getFinalString(),
			], ' ');
			$this->sql->setValue($sql);
			$this->sql->generateStringStatement();

			return [
				self::SQL => $this->sql->getFinalString(),
				self::BINDS => $this->binds->getBinds()
			];
		}
	}
