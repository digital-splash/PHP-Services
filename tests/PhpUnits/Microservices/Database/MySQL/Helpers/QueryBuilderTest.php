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

		//TODO: Add Cases with JOIN
		public function deleteAllCasesSuccessProvider(): array {
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
		 * @dataProvider deleteAllCasesSuccessProvider
		 */
		public function testDeleteAllCasesSuccess(
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

		public function selectAllCasesSuccessProvider(): array {
			return [
				'select_all' => [
					'data' => [''],
					'expected_sql' => 'SELECT * FROM `db`.`table`',
					'expected_binds' => []
				],
				'select_without_where' =>[
					'data' => [
						'name',
						'email',
						'age',
					],
					'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table`',
					'expected_binds' => []
				],
				'select_with_where' => [
					'data' => [
						'name',
						'email',
						'age',
					],
					'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table` WHERE `id` = :id AND `name` = :name AND `age` = :age',
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
					],
					'where' => [
						'id' => 1,
						'name' => 'Hadi Darwish',
						'age' => 22
					]
				],
				'select_with_join' => [
					'data' => [
						'name',
						'email',
						'age',
					],
					'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table` INNER JOIN `db`.`users` ON `users`.`id` = `table`.`user_id`',
					'expected_binds' => [],
                    'where' => [],
					'join' => [
						'INNER JOIN `db`.`users` ON `users`.`id` = `table`.`user_id`'
                    ]
				],
				'select_with_order' => [
					'data' => [
						'name',
						'email',
						'age',
					],
					'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table` ORDER BY `users`.`id` DESC',
					'expected_binds' => [],
                    'where' => [],
                    'join' => [],
					'order' => [
						'`users`.`id` DESC'
					]
				],
				'select_with_limit' => [
					'data' => [
						'name',
						'email',
						'age',
					],
					'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table` LIMIT 10',
					'expected_binds' => [],
                    'where' => [],
                    'join' => [],
					'order' => [],
					'limit' => 10
				],
				'select_wit_offset' => [
					'data' => [
						'name',
						'email',
						'age',
					],
					'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table` OFFSET 5',
					'expected_binds' => [],
                    'where' => [],
                    'join' => [],
					'order' => [],
					'limit' => null,
					'offset' => 5
				],
				'select_with_group' => [
					'data' => [
						'name',
						'email',
						'age',
					],
					'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table` GROUP BY users.id',
					'expected_binds' => [],
                    'where' => [],
                    'join' => [],
					'order' => [],
					'limit' => null,
					'offset' => null,
					'group' => [
						'users.id'
					]
				],
                'select_with_having' => [
                    'data' => [
                        'name',
                        'email',
                        'age',
                    ],
                    'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table` HAVING `users.id` = :users.id',
                    'expected_binds' => [
                        ':users.id' => [
                            'value' => 1,
                            'type' => 1
                        ]
                    ],
                    'where' => [],
                    'join' => [],
                    'order' => [],
                    'limit' => null,
                    'offset' => null,
                    'group' => [],
                    'having' => [
                        'users.id' => 1
                    ]
                ],
				'select_with_all_cases' => [
					'data' => [
						'name',
						'email',
						'age',
					],
					'expected_sql' => 'SELECT `name`, `email`, `age` FROM `db`.`table` INNER JOIN `db`.`users` ON `users`.`id` = `table`.`user_id` WHERE `id` = :id AND `name` = :name AND `age` = :age GROUP BY users.id HAVING `users.id` = :users.id ORDER BY `users`.`id` DESC LIMIT 10 OFFSET 5',
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
						],
						':users.id' => [
							'value' => 1,
							'type' => 1
						]
					],
					'where' => [
						'id' => 1,
						'name' => 'Hadi Darwish',
						'age' => 22
					],
					'join' => [
						'INNER JOIN `db`.`users` ON `users`.`id` = `table`.`user_id`'
					],
					'order' => [
						'`users`.`id` DESC'
					],
					'limit' => 10,
					'offset' => 5,
					'group' => [
						'users.id'
					],
					'having' => [
						'users.id' => 1
					]
				]
			];
		}

		/**
		 * @dataProvider selectAllCasesSuccessProvider
		 */
		public function testSelectAllCasesSuccess(
			array $data,
			string $expectedSql,
			array $expectedBinds,
			array $where = [],
			array $join = [],
			array $order = [],
			int $limit = null,
			int $offset = null,
			array $group = [],
			array $having = []
		): void {
			$db = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($db, $table);
			$queryBuilder->data->setData($data);
			$queryBuilder->where->setArray($where);
			$queryBuilder->join->setArray($join);
			$queryBuilder->order->setArray($order);
			$queryBuilder->limit->setValue($limit);
			$queryBuilder->offset->setValue($offset);
			$queryBuilder->group->setArray($group);
			$queryBuilder->having->setArray($having);
			[
				'sql' => $sql,
				'binds' => $binds
			] = $queryBuilder->select();

			$this->assertEquals($expectedSql, $sql);
			$this->assertEquals($expectedBinds, $binds);
		}

	}
