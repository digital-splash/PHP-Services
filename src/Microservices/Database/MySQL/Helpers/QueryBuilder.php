<?php
	namespace DigitalSplash\Database\MySQL\Helpers;

	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use PDO;

	class QueryBuilder {
		const SQL = 'sql';
		const BINDS = 'binds';

		protected string $database = '';
		protected string $table = '';
		protected string $sql = '';

		protected array $binds = [];

		protected array $data = [];
		protected string $where_str = '';
		protected string $join_str = '';
		protected string $group_str = '';
		protected string $having_str = '';
		protected string $order_str = '';
		protected string $limit_str = '';
		protected string $offset_str = '';

		protected array $where = [];
		protected array $join = [];
		protected array $group = [];
		protected array $having = [];
		protected array $order = [];
        protected int $limit = 0;
        protected int $offset = 0;

		public function __construct(
			string $database,
			string $table
		) {

            //throw error if empty
            if (Helper::StringNullOrEmpty($database)) {
                throw new NotEmptyParamException('database');
            }

            if (Helper::StringNullOrEmpty($table)) {
                throw new NotEmptyParamException('table');
            }
            
			$this->database = $database;
			$this->table = $table;
		}

		public static function GetPDOTypeFromValue(
			$value
		): int {
			$type = PDO::PARAM_STR;

			$valueType = gettype($value);
			if ($valueType === 'integer' || $valueType === 'double') {
				$type = PDO::PARAM_INT;
			}

			return $type;
		}

		//BEGIN: Getters and Setters
		public function getDatabase(): string {
			return $this->database;
		}

		public function getTable(): string {
			return $this->table;
		}

		public function getSql(): string {
			return $this->sql;
		}

		public function setSql(string $sql) : void {
			$this->sql = $sql;
		}

		public function clearSql() : void {
			$this->setSql('');
		}

		public function getBinds(): array {
			return $this->binds;
		}

		public function setBinds(array $data) : void {
			$this->binds = $data;
		}

		public function clearBinds() : void {
			$this->setBinds([]);
		}

		public function appendToBind(string $key, $value) : void {
			$this->binds[$key] = $value;
		}

		public function getData() : array {
			return $this->data;
		}

		public function setData(array $data) : void {
			$this->data = $data;
		}

        public function clearData() : void {
            $this->setData([]);
        }

        public function appendToData(string $key, $value) : void {
            $this->data[$key] = $value;
        }

		public function getWhere() : array {
			return $this->where;
		}

		public function setWhere(array $where) : void {
			$this->where = $where;
		}

        public function clearWhere() : void {
            $this->setWhere([]);
        }
        
		public function appendToWhere(string $key, $value) : void {
			$this->where[$key] = $value;
		}

		public function getJoin() : array {
			return $this->join;
		}

		public function setJoin(array $join) : void {
			$this->join = $join;
		}

        public function clearJoin() : void {
            $this->setJoin([]);
        }
        
		public function appendToJoin(string $value) : void {
			$this->join[] = $value;
		}

		public function getGroup() : array {
			return $this->group;
		}

		public function setGroup(array $group) : void {
			$this->group = $group;
		}

        public function clearGroup() : void {
            $this->setGroup([]);
        }

		public function appendToGroup(string $value) : void {
			$this->group[] = $value;
		}

		public function getHaving() : array {
			return $this->having;
		}

		public function setHaving(array $having) : void {
			$this->having = $having;
		}

        public function clearHaving() : void {
            $this->setHaving([]);
        }

		public function appendToHaving(string $value) : void {
			$this->having[] = $value;
		}

		public function getOrder() : array {
			return $this->order;
		}

		public function setOrder(array $order) : void {
			$this->order = $order;
		}

        public function clearOrder() : void {
            $this->setOrder([]);
        }

		public function appendToOrder(string $value) : void {
			$this->order[] = $value;
		}

		public function getLimit() : int {
			return $this->limit;
		}

		public function setLimit(int $limit) : void {
			$this->limit = $limit;
		}

        public function clearLimit() : void {
            $this->setLimit(0);
        }

		public function getOffset() : int {
			return $this->offset;
		}

		public function setOffset(int $offset) : void {
			$this->offset = $offset;
		}

        public function clearOffset() : void {
            $this->setOffset(0);
        }

        public function getWhereStr() : string {
            return $this->where_str;
        }

        protected function setWhereStr(string $where_str) : void {
            $this->where_str = $where_str;
        }

        public function clearWhereStr() : void {
            $this->setWhereStr('');
        }
		//END: Getters and Setters

        public function insert(): array {
            if (Helper::ArrayNullOrEmpty($this->data)) {
				throw new NotEmptyParamException('data');
			}
        
            $columns = [];
            $this->clearBinds();
            $rows= [];
            $i = 1;
            foreach ($this->data as $row) {
                $rowColumns = [];
                foreach ($row as $column => $value) {
                    if (!in_array("`{$column}`", $columns)) {
                        $columns[] = "`{$column}`";
                    }
                    $bind_key = ":{$column}_{$i}";
                    $bind_arr = [
                        'value' => $value,
                        'type' => self::GetPDOTypeFromValue($value)
                    ];
                    $this->appendToBind($bind_key, $bind_arr);
                    $rowColumns[] = $bind_key;
                }
                $rows[] = '(' . implode(', ', $rowColumns) . ')';
                $i++;
            }
        
            $columnsStr = Helper::ImplodeArrToStr($columns, ', ');
            $rowsStr = implode(', ', $rows);
        
            $sql = "INSERT INTO `{$this->database}`.`{$this->table}` ($columnsStr) VALUES $rowsStr";
            $this->setSql($sql);
        
            return [
                self::SQL => $this->getSql(),
                self::BINDS => $this->getBinds()
            ];
        }


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




		public function getWhereStatement(): void {
			if (!Helper::ArrayNullOrEmpty($this->where)) {
				$whereStr = '';
				foreach ($this->where AS $column => $value) {
					$whereStr .= "`{$column}` = :{$column} AND ";
					$bind_key = ':' . $column;

                    $this->appendToBind(
                            $bind_key,
                            [
                                'value' => $value,
                                'type' => self::GetPDOTypeFromValue($value)
                            ]
                        );

					$binds[$bind_key] = [
						'value' => $value,
						'type' => self::GetPDOTypeFromValue($value)
					];
				}
				$whereStr = rtrim($whereStr, ' AND ');
				$this->where_str = " WHERE $whereStr";

			} else {
				$this->where_str = '';
			}

		}

		// public function getJoinStatement(): void {
		// 	if (!Helper::ArrayNullOrEmpty($this->joinValues)) {
		// 		$joinStr = '';
		// 		foreach ($this->joinValues as $joinData) {
		// 			$joinStr .= " {$joinData['type']} JOIN {$joinData['table']} ON {$joinData['on']}";
		// 		}
		// 		$this->join = $joinStr;
		// 	} else {
		// 		$this->join = '';
		// 	}
		// }

		// public function getOrderByStatement(): string {
		// 	if (!empty($this->order)) {
		// 		$this->order = " ORDER BY $this->order";
		// 	} else {
		// 		$this->order = '';
		// 	}

		// 	return $this->order;
		// }

		// public function getLimitStatement(): void {
		// 	if (!empty($this->limit)) {
		// 		$this->limit = " LIMIT $this->limit";
		// 	} else {
		// 		$this->limit = '';
		// 	}

		// }

		// public function getHavingStatement(): void {

		// 	if (!Helper::ArrayNullOrEmpty($this->havingValues)) {
		// 		$havingStr = '';
		// 		$binds = [];
		// 		foreach ($this->havingValues AS $column => $value) {
		// 			$havingStr .= "`{$column}` = :{$column} AND ";
		// 			$bind_key = ':' . $column;

		// 			$binds[$bind_key] = [
		// 				'value' => $value,
		// 				'type' => self::GetPDOTypeFromValue($value)
		// 			];
		// 		}
		// 		$havingStr = rtrim($havingStr, ' AND ');
		// 		$this->having = " HAVING $havingStr";
		// 		$this->_binds = array_merge($this->_binds, $binds);

		// 	} else {
		// 		$this->having = '';
		// 	}

		// }

		// public function getSuffixStatement(): string {
		// 	if (!empty($this->suffix)) {
		// 		$this->suffix = " $this->suffix";
		// 	} else {
		// 		$this->suffix = '';
		// 	}

		// 	return $this->suffix;
		// }

		// public function getGroupByStatement(): string {
		// 	if (!empty($this->group)) {
		// 		$this->group = " GROUP BY $this->group";
		// 	} else {
		// 		$this->group = '';
		// 	}

		// 	return $this->group;
		// }

	}
