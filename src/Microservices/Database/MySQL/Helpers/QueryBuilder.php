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
        protected string $group = '';
		protected string $having = '';
		protected string $order = '';
		protected string $limit = '';
		protected string $suffix = '';
        protected string $where = '';
        protected array $_binds;
        protected array $whereData = [];
        protected array $joinValues = [];
        protected array $havingValues = [];
        protected string $join = '';
        protected array $data = [];

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

        public function getData() : array {
            return $this->data;
        }

        public function setData(array $data) : void {
            $this->data = $data;
        }
        public function getWhere() : array {
            return $this->whereData;
        }

        public function setWhere(array $where) : void {
            $this->whereData = $where;
        }

        public function getJoin() : array {
            return $this->joinValues;
        }

        public function setJoin(array $join) : void {
            $this->joinValues = $join;
        }

        public function getGroup() : string {
            return $this->group;
        }

        public function setGroup(string $group) : void {
            $this->group = $group;
        }

        public function getHaving() : array {
            return $this->havingValues;
        }

        public function setHaving(array $having) : void {
            $this->havingValues = $having;
        }

        public function getOrder() : string {
            return $this->order;
        }

        public function setOrder(string $order) : void {
            $this->order = $order;
        }

        public function getLimit() : string {
            return $this->limit;
        }

        public function setLimit(string $limit) : void {
            $this->limit = $limit;
        }

        public function getSuffix() : string {
            return $this->suffix;
        }

        public function setSuffix(string $suffix) : void {
            $this->suffix = $suffix;
        }
        

        

		public function insert(): array {
			if (Helper::ArrayNullOrEmpty($this->data)) {
				throw new NotEmptyParamException('data');
			}

			$columns = [];
			$binds = [];

			foreach ($this->data AS $column => $value) {
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

		public function update(): array {
			if (Helper::ArrayNullOrEmpty($this->data)) {
				throw new NotEmptyParamException('data');
			}

			$columns = [];
			$binds = [];
            $columnsStr = '';
			foreach ($this->data AS $column => $value) {
				if (!in_array($column, $columns)) {
					$columns[] = "`{$column}`";
				}
				$bind_key = ':' . $column;

				$binds[$bind_key] = [
					'value' => $value,
					'type' => self::GetPDOTypeFromValue($value)
				];

                $columnsStr .= "`{$column}` = :{$column}, ";

			}
            
            $this->getWhereStatement();
            $binds = array_merge($binds, $this->_binds);

            $columnsStr = rtrim($columnsStr, ', ');
            $sql = "UPDATE {$this->database}.{$this->table} SET $columnsStr" . $this->where;

			return [
				self::SQL => $sql,
				self::BINDS => $binds
			];
		}

		public function delete(): array {

            if (Helper::ArrayNullOrEmpty($this->whereData)) {
				throw new NotEmptyParamException('whereData');
			}

            $this->getWhereStatement();
			
            $sql = "DELETE FROM {$this->database}.{$this->table}" . $this->where;
            
			return [
				self::SQL => $sql,
                self::BINDS => $this->_binds
			];
		}

		public function select(): array {

            if (Helper::ArrayNullOrEmpty($this->data)) {
				throw new NotEmptyParamException('data');
			}

			$columnsStr = '*';

			if (!Helper::ArrayNullOrEmpty($this->data)) {
				$columnsStr = Helper::ImplodeArrToStr($this->data, ', ');
			}

            $this->getWhereStatement();

            $this->getJoinStatement();

            $this->getLimitStatement();

            $this->getOrderByStatement();

            $this->getGroupByStatement();

            $this->getHavingStatement();

            $this->getSuffixStatement();
            


            $sql = "SELECT $columnsStr FROM {$this->database}.{$this->table}"
                    . $this->join   . $this->where
                    . $this->group  . $this->having
                    . $this->order  . $this->limit
                    . $this->suffix;

			return [
				self::SQL => $sql,
                self::BINDS => $this->_binds
			];
		}



		
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

        protected function getWhereStatement(): void {
            if (!Helper::ArrayNullOrEmpty($this->whereData)) {
				$whereStr = '';
                $binds = [];
				foreach ($this->whereData AS $column => $value) {
					$whereStr .= "`{$column}` = :{$column} AND ";
					$bind_key = ':' . $column;

					$binds[$bind_key] = [
						'value' => $value,
						'type' => self::GetPDOTypeFromValue($value)
					];
				}
				$whereStr = rtrim($whereStr, ' AND ');
                $this->where = " WHERE $whereStr";
                $this->_binds = $binds;

			} else {
                $this->where = '';
                $this->_binds = [];
            }
            
            // return [self::BINDS => $this->_binds];

        }

        public function getJoinStatement(): void {
            if (!Helper::ArrayNullOrEmpty($this->joinValues)) {
                $joinStr = '';
                foreach ($this->joinValues as $joinData) {
                    $joinStr .= " {$joinData['type']} JOIN {$joinData['table']} ON {$joinData['on']}";
                }
                $this->join = $joinStr;
            } else {
                $this->join = '';
            }
        }

        public function getOrderByStatement(): string {
            if (!empty($this->order)) {
                $this->order = " ORDER BY $this->order";
            } else {
                $this->order = '';
            }
            
            return $this->order;
        }

        public function getLimitStatement(): void {
            if (!empty($this->limit)) {
                $this->limit = " LIMIT $this->limit";
            } else {
                $this->limit = '';
            }
            
        }

        public function getHavingStatement(): void {

            if (!Helper::ArrayNullOrEmpty($this->havingValues)) {
                $havingStr = '';
                $binds = [];
                foreach ($this->havingValues AS $column => $value) {
                    $havingStr .= "`{$column}` = :{$column} AND ";
                    $bind_key = ':' . $column;

                    $binds[$bind_key] = [
                        'value' => $value,
                        'type' => self::GetPDOTypeFromValue($value)
                    ];
                }
                $havingStr = rtrim($havingStr, ' AND ');
                $this->having = " HAVING $havingStr";
                $this->_binds = array_merge($this->_binds, $binds);

            } else {
                $this->having = '';
            }
           
        }

        public function getSuffixStatement(): string {
            if (!empty($this->suffix)) {
                $this->suffix = " $this->suffix";
            } else {
                $this->suffix = '';
            }
            
            return $this->suffix;
        }

        public function getGroupByStatement(): string {
            if (!empty($this->group)) {
                $this->group = " GROUP BY $this->group";
            } else {
                $this->group = '';
            }
            
            return $this->group;
        }
	}