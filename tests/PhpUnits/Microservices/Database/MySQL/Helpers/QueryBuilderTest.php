<?php
	namespace DigitalSplash\Tests\Database\MySQL\Helpers;

	use DigitalSplash\Database\MySQL\Helpers\QueryBuilder;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
	use PDO;
	use PHPUnit\Framework\TestCase;

	class QueryBuilderTest extends TestCase {

		public function constructorThrowsProvider(): array {
			return [
				'empty database and table' => [
					'',
					''
				],
				'empty database' => [
					 '',
					'table'
				],
				'empty table' => [
					'db',
					''
				]
			];
		}

		/**
		 * @dataProvider constructorThrowsProvider
		 */
		public function testConstructorThrows(
			string $database,
			string $table
		): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => empty($database) ? 'database' : 'table'
			]));

			new QueryBuilder($database, $table);
		}

		public function testConstructorSuccess(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals($database, $queryBuilder->getDatabase());
			$this->assertEquals($table, $queryBuilder->getTable());
		}

		public function getPDOTypeFromValueProvider(): array {
			return [
				'null' => [
					null,
					PDO::PARAM_STR
				],
				'int' => [
					1,
					PDO::PARAM_INT
				],
				'string' => [
					'string',
					PDO::PARAM_STR
				],
				'bool' => [
					true,
					PDO::PARAM_STR
				],
				'double' => [
					1.1,
					PDO::PARAM_INT
				]
			];
		}

		/**
		 * @dataProvider getPDOTypeFromValueProvider
		 */
		public function testGetPDOTypeFromValue(
			$value,
			int $expected
		): void {
			$queryBuilder = new QueryBuilder('db', 'table');
			$this->assertEquals($expected, $queryBuilder->getPDOTypeFromValue($value));
		}

		public function testGetDatabase(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals($database, $queryBuilder->getDatabase());
		}

		public function testGetTable(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals($table, $queryBuilder->getTable());
		}


		public function testInsertNoDataToInsertThrows(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "data"
			]));

			$queryBuilder = new QueryBuilder('db', 'table');
			$queryBuilder->insert();
		}

		public function testInsertSingleRecordSuccess(): void {
			$db = 'db';
			$table = 'table';
			$data = [
				[
					'name' => 'Hadi Darwish',
					'email' => 'hadi@example.com',
					'age' => 22,
				]
			];

			$queryBuilder = new QueryBuilder($db, $table);
			$queryBuilder->data->setData($data);
			[
				'sql' => $sql,
				'binds' => $binds
			] = $queryBuilder->insert();

			$expectedSql = "INSERT INTO `{$db}`.`{$table}` (`name`, `email`, `age`) VALUES (:name_1, :email_1, :age_1)";
			$expectedBinds = [];

			foreach ($data AS $row) {
				foreach ($row AS $column => $value) {
					$bind_key = ":{$column}_1";
					$expectedBinds[$bind_key] = [
						'value' => $value,
						'type' => QueryBuilder::GetPDOTypeFromValue($value)
					];
				}
			}

			$this->assertEquals($expectedSql, $sql);
			$this->assertEqualsCanonicalizing($expectedBinds, $binds);
		}

		public function testInsertInBulk(): void {
			$db = 'db';
			$table = 'table';
			$data = [
				['name' => 'John', 'age' => 25],
				['name' => 'Jane', 'age' => 30],
				['name' => 'Bob', 'age' => 40],
			];
			$queryBuilder = new QueryBuilder($db, $table);
			$queryBuilder->data->setData($data);
			[
				'sql' => $sql,
				'binds' => $binds
			] = $queryBuilder->insert();

			$expectedSql = "INSERT INTO `db`.`table` (`name`, `age`) VALUES (:name_1, :age_1), (:name_2, :age_2), (:name_3, :age_3)";
			$this->assertEquals($expectedSql,$sql);

			$expectedBinds = [
				':name_1' => [
					'value' => 'John',
					'type' => PDO::PARAM_STR
				],
				':age_1' => [
					'value' => 25,
					'type' => PDO::PARAM_INT
				],
				':name_2' => [
					'value' => 'Jane',
					'type' => PDO::PARAM_STR
				],
				':age_2' => [
					'value' => 30,
					'type' => PDO::PARAM_INT
				],
				':name_3' => [
					'value' => 'Bob',
					'type' => PDO::PARAM_STR
				],
				':age_3' => [
					'value' => 40,
					'type' => PDO::PARAM_INT
				],
			];
			$this->assertEquals($expectedBinds, $binds);
		}

		// public function testUpdateNoDataToUpdateThrows(): void {
		// 	$this->expectException(NotEmptyParamException::class);
		// 	$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
		// 		"::params::" => "data"
		// 	]));

		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->update();
		// }

		// public function testUpdateSingleRecordSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$values = [
		// 		'name' => 'Hadi Darwish',
		// 		'email' => 'hadi@example.com',
		// 		'age' => 22,
		// 	];

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($values);
		// 	[
		// 		'sql' => $sql,
		// 		'binds' => $binds
		// 	] = $queryBuilder->update();

		// 	$expectedSql = "UPDATE {$db}.{$table} SET `name` = :name, `email` = :email, `age` = :age";
		// 	$expectedBinds = [];
		// 	foreach ($values as $column => $value) {
		// 		$bind_key = ':' . $column;
		// 		$expectedBinds[$bind_key] = [
		// 			'value' => $value,
		// 			'type' => QueryBuilder::GetPDOTypeFromValue($value)
		// 		];
		// 	}

		// 	$this->assertEquals($expectedSql, $sql);
		// 	$this->assertEqualsCanonicalizing($expectedBinds, $binds);
		// }

		// public function testUpdateSingleRecordWithWhereSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$values = [
		// 		'name' => 'Hadi Darwish',
		// 		'email' => 'hadi@example.com',
		// 		'age' => 22,
		// 	];
		// 	$where = [
		// 		'id' => 1,
		// 	];

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($values);
		// 	$queryBuilder->setWhere($where);
		// 	[
		// 		'sql' => $sql,
		// 		'binds' => $binds
		// 	] = $queryBuilder->update();

		// 	$expectedSql = "UPDATE {$db}.{$table} SET `name` = :name, `email` = :email, `age` = :age WHERE `id` = :id";
		// 	$expectedBinds = [];
		// 	foreach ($values as $column => $value) {
		// 		$bind_key = ':' . $column;
		// 		$expectedBinds[$bind_key] = [
		// 			'value' => $value,
		// 			'type' => QueryBuilder::GetPDOTypeFromValue($value)
		// 		];

		// 	}
		// 	foreach ($where as $column => $value) {
		// 		$bind_key = ':' . $column;
		// 		$expectedBinds[$bind_key] = [
		// 			'value' => $value,
		// 			'type' => QueryBuilder::GetPDOTypeFromValue($value)
		// 		];
		// 	}

		// 	$this->assertEquals($expectedSql, $sql);
		// 	$this->assertEqualsCanonicalizing($expectedBinds, $binds);
		// }

		public function deleteProvider(): array {
			return [
				[
					'where' => [],
					'expected_sql' => 'DELETE FROM `db`.`table`',
					'expected_binds' => []
				],
				[
					'where' => [
						'id' => 1,
					],
					'expected_sql' => 'DELETE FROM `db`.`table` WHERE `id` = :id',
					'expected_binds' => [
						':id' => [
							'value' => 1,
							'type' => 1
						]
					]
				],
				[
					'where' => [
						'id' => 1,
						'name' => 'Hadi Darwish'
					],
					'expected_sql' => 'DELETE FROM `db`.`table` WHERE `id` = :id AND `name` = :name',
					'expected_binds' => [
						':id' => [
							'value' => 1,
							'type' => 1
						],
						':name' => [
							'value' => 'Hadi Darwish',
							'type' => 2
						]
					]
				],
				[
						'where' => [
							'id' => 1,
							'name' => 'Hadi Darwish',
							'age' => 22
						],
						'expected_sql' => 'DELETE FROM `db`.`table` WHERE `id` = :id AND `name` = :name AND `age` = :age',
						'expected_binds' => [
							':id' => [
								'value' => 1,
								'type' => 1
							],
							':name' => [
								'value' => 'Hadi Darwish',
								'type' => 2
							],
							':age' => [
								'value' => 22,
								'type' => 1
							]
						]
				],
			];
		}

		/**
		 * @dataProvider deleteProvider
		 */
		public function testDeleteSuccess(
			array $where,
			string $expected_sql,
			array $expected_binds
		): void {
			$db = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($db, $table);
			$queryBuilder->where->setArray($where);
			[
				'sql' => $sql,
				'binds' => $binds
			] = $queryBuilder->delete();

			$this->assertEquals($expected_sql, $sql);
			$this->assertEqualsCanonicalizing($expected_binds, $binds);
		}

		public function testSelectNoDataToSelectThrows(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "data"
			]));

			$queryBuilder = new QueryBuilder('db', 'table');
			$queryBuilder->select();
		}

		// public function testSelectSingleRecordSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$columns = [
		// 		'name',
		// 		'email',
		// 		'age',
		// 	];

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($columns);
		// 	[
		// 		'sql' => $sql
		// 	] = $queryBuilder->select();

		// 	$expectedSql = 'SELECT name, email, age FROM db.table';

		// 	$this->assertEquals($expectedSql, $sql);
		// }

		// public function testSelectSingleRecordWithWhereSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$columns = [
		// 		'name',
		// 		'email',
		// 		'age',
		// 	];
		// 	$where = [
		// 		'id' => 1,
		// 	];

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($columns);
		// 	$queryBuilder->setWhere($where);
		// 	[
		// 		'sql' => $sql
		// 	] = $queryBuilder->select();

		// 	$expectedSql = 'SELECT name, email, age FROM db.table WHERE `id` = :id';

		// 	$this->assertEquals($expectedSql, $sql);
		// }

		// public function testSelectSingleRecordWithWhereAndLimitSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$columns = [
		// 		'name',
		// 		'email',
		// 		'age',
		// 	];
		// 	$where = [
		// 		'id' => 1,
		// 	];
		// 	$limit = 1;

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($columns);
		// 	$queryBuilder->setWhere($where);
		// 	$queryBuilder->setLimit($limit);
		// 	[
		// 		'sql' => $sql
		// 	] = $queryBuilder->select();

		// 	$expectedSql = 'SELECT name, email, age FROM db.table WHERE `id` = :id LIMIT 1';

		// 	$this->assertEquals($expectedSql, $sql);
		// }

		// public function testSelectSingleRecordWithWhereAndLimitAndOffsetSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$columns = [
		// 		'name',
		// 		'email',
		// 		'age',
		// 	];
		// 	$where = [
		// 		'id' => 1,
		// 	];
		// 	$limit = 1;
		// 	$offset = 1;

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($columns);
		// 	$queryBuilder->setWhere($where);
		// 	$queryBuilder->setLimit($limit);
		// 	[
		// 		'sql' => $sql
		// 	] = $queryBuilder->select();

		// 	$expectedSql = 'SELECT name, email, age FROM db.table WHERE `id` = :id LIMIT 1 OFFSET 1';

		// 	$this->assertEquals($expectedSql, $sql);
		// }

		// public function testSelectSingleRecordWithWhereAndLimitAndOrderBySuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$columns = [
		// 		'name',
		// 		'email',
		// 		'age',
		// 	];
		// 	$where = [
		// 		'id' => 1,
		// 	];
		// 	$limit = 1;
		// 	$orderBy = 'name ASC';

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($columns);
		// 	$queryBuilder->setWhere($where);
		// 	$queryBuilder->setLimit($limit);
		// 	$queryBuilder->setOrder($orderBy);
		// 	[
		// 		'sql' => $sql
		// 	] = $queryBuilder->select();

		// 	$expectedSql = 'SELECT name, email, age FROM db.table WHERE `id` = :id ORDER BY name ASC LIMIT 1';

		// 	$this->assertEquals($expectedSql, $sql);
		// }

		// public function testSelectSingleRecordWithWhereAndLimitAndOrderByAndGroupBySuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$columns = [
		// 		'name',
		// 		'email',
		// 		'age',
		// 	];
		// 	$where = [
		// 		'id' => 1,
		// 	];
		// 	$limit = 1;
		// 	$orderBy = 'name ASC';
		// 	$groupBy = 'name';

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($columns);
		// 	$queryBuilder->setWhere($where);
		// 	$queryBuilder->setLimit($limit);
		// 	$queryBuilder->setOrder($orderBy);
		// 	$queryBuilder->setGroup($groupBy);
		// 	[
		// 		'sql' => $sql
		// 	] = $queryBuilder->select();

		// 	$expectedSql = 'SELECT name, email, age FROM db.table WHERE `id` = :id GROUP BY name ORDER BY name ASC LIMIT 1';

		// 	$this->assertEquals($expectedSql, $sql);
		// }

		// public function testSelectSingleRecordWithWhereAndLimitAndOrderByAndGroupByAndHavingSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$columns = [
		// 		'name',
		// 		'email',
		// 		'age',
		// 	];
		// 	$where = [
		// 		'id' => 1,
		// 	];
		// 	$limit = 1;
		// 	$orderBy = 'name ASC';
		// 	$groupBy = 'name';
		// 	$having = [
		// 		'name' => 'test',
		// 		'age' => 2
		// 	];

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($columns);
		// 	$queryBuilder->setWhere($where);
		// 	$queryBuilder->setLimit($limit);
		// 	$queryBuilder->setOrder($orderBy);
		// 	$queryBuilder->setGroup($groupBy);
		// 	$queryBuilder->setHaving($having);
		// 	[
		// 		'sql' => $sql
		// 	] = $queryBuilder->select();

		// 	$expectedSql = 'SELECT name, email, age FROM db.table WHERE `id` = :id GROUP BY name HAVING `name` = :name AND `age` = :age ORDER BY name ASC LIMIT 1';
		// 	$this->assertEquals($expectedSql, $sql);

		// }


		// public function testSelectSingleRecordWithWhereAndLimitAndOrderByAndGroupByAndHavingAndJoinSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$columns = [
		// 		'name',
		// 		'email',
		// 		'age',
		// 	];
		// 	$where = [
		// 		'id' => 1,
		// 	];
		// 	$limit = 1;
		// 	$orderBy = 'name ASC';
		// 	$groupBy = 'name';
		// 	$having = [
		// 		'name' => 'test',
		// 		'age' => 2
		// 	];
		// 	$join = [
		// 		'table' => 'table2',
		// 		'on' => 'table.id = table2.id',
		// 		'type' => 'LEFT'
		// 	];

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setData($columns);
		// 	$queryBuilder->setWhere($where);
		// 	$queryBuilder->setLimit($limit);
		// 	$queryBuilder->setOrder($orderBy);
		// 	$queryBuilder->setGroup($groupBy);
		// 	$queryBuilder->setHaving($having);
		// 	$queryBuilder->setJoin($join);
		// 	[
		// 		'sql' => $sql
		// 	] = $queryBuilder->select();

		// 	$expectedSql = 'SELECT name, email, age FROM db.table LEFT JOIN db.table2 ON table.id = table2.id WHERE `id` = :id GROUP BY name HAVING `name` = :name AND `age` = :age ORDER BY name ASC LIMIT 1';
		// 	$this->assertEquals($expectedSql, $sql);

		// }

		// public function getWhereStatementProvider() : array {
		// 	return [
		// 		'empty where' => [
		// 			'where' => [],
		// 			'expectedSql' => '',
		// 			'expectedBinds' => []
		// 		],
		// 		'where with one column' => [
		// 			'where' => [
		// 				'id' => 1
		// 			],
		// 			'expectedSql' => ' WHERE `id` = :id',
		// 			'expectedBinds' => [
		// 				':id' => [
		// 					'value' => 1,
		// 					'type' => 1
		// 				]
		// 			]
		// 		],
		// 		'where with many columns' => [
		// 			'where' => [
		// 				'id' => 1,
		// 				'name' => 'test',
		// 				'age' => 2
		// 			],
		// 			'expectedSql' => ' WHERE `id` = :id AND `name` = :name AND `age` = :age',
		// 			'expectedBinds' => [
		// 				':id' => [
		// 					'value' => 1,
		// 					'type' => 1
		// 				],
		// 				':name' => [
		// 					'value' => 'test',
		// 					'type' => 2
		// 				],
		// 				':age' => [
		// 					'value' => 2,
		// 					'type' => 1
		// 				]
		// 			]
		// 		],
		// 	];

		// }

		// /**
		//  * @dataProvider getWhereStatementProvider
		//  */
		// public function testGetWhereStatementSuccess(array $where, string $expectedSql, array $expectedBinds) : void {
		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->setWhere($where);
		// 	$queryBuilder->getWhereStatement();

		// 	$this->assertEquals($expectedSql, $queryBuilder->getWhereStr());
		// 	$this->assertEquals($expectedBinds, $queryBuilder->getBinds());

		// }

		// public function getJoinStatementProvider() : array {
		// 	return [
		// 		'empty join' => [
		// 			'join' => [],
		// 			'expectedSql' => '',
		// 		],
		// 		'join with one column' => [
		// 			['LEFT JOIN db.table2 ON table.id = table2.id'],
		// 			'expectedSql' => 'LEFT JOIN db.table2 ON table.id = table2.id',
		// 		],
		// 		'join with many columns' => [
		// 			'join' => [
		// 				'LEFT JOIN db.table2 ON table.id = table2.id',
		// 				'RIGHT JOIN db.table3 ON table.id = table3.id',
		// 			],
		// 			'expectedSql' => 'LEFT JOIN db.table2 ON table.id = table2.id, RIGHT JOIN db.table3 ON table.id = table3.id',
		// 		],
		// 	];

		// }

		// /**
		//  * @dataProvider getJoinStatementProvider
		//  */
		// public function testGetJoinStatementSuccess(array $join, string $expectedSql) : void {
		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->setJoin($join);
		// 	$queryBuilder->getJoinStatement();

		// 	$this->assertEquals($expectedSql, $queryBuilder->getJoinStr());

		// }

		// public function getOrderByStatementProvider() : array {
		// 	return [
		// 		'empty order by' => [
		// 			'orderBy' => [],
		// 			'expectedSql' => '',
		// 		],
		// 		'order by with one column' => [
		// 			'orderBy' => ['name ASC'],
		// 			'expectedSql' => ' ORDER BY name ASC',
		// 		],
		// 		'order by with many columns' => [
		// 			'orderBy' => ['name ASC', 'age DESC'],
		// 			'expectedSql' => ' ORDER BY name ASC, age DESC',
		// 		],
		// 	];

		// }

		// /**
		//  * @dataProvider getOrderByStatementProvider
		//  */
		// public function testGetOrderByStatementSuccess(array $orderBy, string $expectedSql) : void {
		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->setOrder($orderBy);
		// 	$queryBuilder->getOrderByStatement();

		// 	$this->assertEquals($expectedSql, $queryBuilder->getOrderStr());

		// }

		// public function getLimitStatementProvider() : array {
		// 	return [
		// 		'empty limit' => [
		// 			'limit' => 0,
		// 			'expectedSql' => '',
		// 		],
		// 		'limit with' => [
		// 			'limit' => 1,
		// 			'expectedSql' => ' LIMIT 1',
		// 		],
		// 	];

		// }

		// /**
		//  * @dataProvider getLimitStatementProvider
		//  */
		// public function testGetLimitStatementSuccess(int $limit, string $expectedSql) : void {
		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->setLimit($limit);
		// 	$queryBuilder->getLimitStatement();

		// 	$this->assertEquals($expectedSql, $queryBuilder->getLimitStr());

		// }

		// public function getHavingStatementProvider() : array {
		// 	return [
		// 		'empty having' => [
		// 			'having' => [],
		// 			'expectedSql' => '',
		// 			'expectedBinds' => []
		// 		],
		// 		'having with one column' => [
		// 			'having' => [
		// 				'id' => 1
		// 			],
		// 			'expectedSql' => ' HAVING `id` = :id',
		// 			'expectedBinds' => [
		// 				':id' => [
		// 					'value' => 1,
		// 					'type' => 1
		// 				]
		// 			]
		// 		],
		// 		'having with many columns' => [
		// 			'having' => [
		// 				'id' => 1,
		// 				'name' => 'test',
		// 				'age' => 2
		// 			],
		// 			'expectedSql' => ' HAVING `id` = :id AND `name` = :name AND `age` = :age',
		// 			'expectedBinds' => [
		// 				':id' => [
		// 					'value' => 1,
		// 					'type' => 1
		// 				],
		// 				':name' => [
		// 					'value' => 'test',
		// 					'type' => 2
		// 				],
		// 				':age' => [
		// 					'value' => 2,
		// 					'type' => 1
		// 				]
		// 			]
		// 		],
		// 	];

		// }

		// /**
		//  * @dataProvider getHavingStatementProvider
		//  */
		// public function testGetHavingStatementSuccess(array $having, string $expectedSql, array $expectedBinds) : void {
		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->setHaving($having);
		// 	$queryBuilder->getHavingStatement();

		// 	$this->assertEquals($expectedSql, $queryBuilder->getHavingStr());
		// 	$this->assertEquals($expectedBinds, $queryBuilder->getBinds());

		// }

		// public function getGroupByStatementProvider() : array {
		// 	return [
		// 		'empty group by' => [
		// 			'groupBy' => [],
		// 			'expectedSql' => '',
		// 		],
		// 		'group by with one column' => [
		// 			'groupBy' => ['name'],
		// 			'expectedSql' => ' GROUP BY name',
		// 		],
		// 		'group by with many columns' => [
		// 			'groupBy' => ['name', 'age'],
		// 			'expectedSql' => ' GROUP BY name, age',
		// 		],
		// 	];

		// }

		// /**
		//  * @dataProvider getGroupByStatementProvider
		//  */
		// public function testGetGroupByStatementSuccess(array $groupBy, string $expectedSql) : void {
		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->setGroup($groupBy);
		// 	$queryBuilder->getGroupByStatement();

		// 	$this->assertEquals($expectedSql, $queryBuilder->getGroupStr());

		// }


	}
