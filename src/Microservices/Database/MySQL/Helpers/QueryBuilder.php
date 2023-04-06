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
        protected string $group;
		protected string $having;
		protected string $order;
		protected string $limit;
		protected string $suffix;
        protected string $where;
        protected array $whereData;
        protected string $join;
        protected array $data;

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
            
            $columnsStr = rtrim($columnsStr, ', ');

            $sql = "UPDATE {$this->database}.{$this->table} SET $columnsStr" . $this->where;

			return [
				self::SQL => $sql,
				self::BINDS => $binds
			];
		}

		public function delete(): array {
			$sql = "DELETE FROM {$this->database}.{$this->table}" . $this->where;

			return [
				self::SQL => $sql,
			];
		}

		public function select(): array {
			$columnsStr = '*';
			if (!Helper::ArrayNullOrEmpty($this->data)) {
				$columnsStr = Helper::ImplodeArrToStr($this->data, ',');
			}

            $sql = "SELECT $columnsStr
                    FROM {$this->database}.{$this->table}"
                    . $this->join . $this->where
                    . $this->group . $this->having
                    . $this->order . $this->limit
                    . $this->suffix;

			return [
				self::SQL => $sql,
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

        protected function getWhereStatement(): string {
            if (!Helper::ArrayNullOrEmpty($this->whereData)) {
				$whereStr = '';
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
			} else {
                $this->where = '';
            }
            
            return $this->where;

        }

        public function getJoinStatement(
            array $values
        ): string {
            if (!Helper::ArrayNullOrEmpty($values)) {
                $joinStr = '';
                foreach ($values AS $joinTable => $joinData) {
                    $joinStr .= " {$joinData['type']} JOIN {$joinTable} ON {$joinData['on']}";
                }
                $this->join = $joinStr;
            } else {
                $this->join = '';
            }

            return $this->join;
        }

        public function getOrderByStatement(
            string $orderBy,
        ): string {
            if (!empty($orderBy)) {
                $this->order = " ORDER BY $orderBy";
            } else {
                $this->order = '';
            }
            
            return $this->order;
        }

        public function getLimitStatement(
            int $limit,
        ): string {
            if (!empty($limit)) {
                $this->limit = " LIMIT $limit";
            } else {
                $this->limit = '';
            }
            
            return $this->limit;
        }

        public function getHavingStatement(
            string $having,
        ): string {
            if (!empty($having)) {
                $this->having = " HAVING $having";
            } else {
                $this->having = '';
            }
            
            return $this->having;
        }

        public function getSuffixStatement(
            string $suffix,
        ): string {
            if (!empty($suffix)) {
                $this->suffix = " $suffix";
            } else {
                $this->suffix = '';
            }
            
            return $this->suffix;
        }

        public function getGroupByStatement(
            string $groupBy,
        ): string {
            if (!empty($groupBy)) {
                $this->group = " GROUP BY $groupBy";
            } else {
                $this->group = '';
            }
            
            return $this->group;
        }
	}