<?php
	namespace DigitalSplash\Tests\Database\MySQL\Helpers;

	use DigitalSplash\Database\MySQL\Helpers\QueryBuilder;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
use PDO;
use PHPUnit\Framework\TestCase;

	class QueryBuilderTest extends TestCase {


        //testing the constructor using data provider

        public function TestConstructorProvider(): array {
            return [
                'empty database' => [
                     '',
                    'table'
                ],
                'empty table' => [
                    'db',
                    ''
                ],
                'empty database and table' => [
                    '',
                    ''
                ]
                // ,
                // 'valid database and table' => [
                //     'db',
                //     'table'
                // ]
            ];
        }

        /**
         * @dataProvider TestConstructorProvider
         */

        // public function testConstructor(
        //     string $database,
        //     string $table
        // ): void {
        //     if (empty($database) || empty($table)) {
        //         $this->expectException(NotEmptyParamException::class);
        //         $this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
        //             "::params::" => empty($database) ? 'database' : 'table'
        //         ]));
        //     }

        //     $queryBuilder = new QueryBuilder($database, $table);
        //     $this->assertEquals($database, $queryBuilder->getDatabase());
        //     $this->assertEquals($table, $queryBuilder->getTable());
        // }


        public function testConstructorThrows(
            string $database,
            string $table
        ): void {
            $this->expectException(NotEmptyParamException::class);
            $this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
                "::params::" => empty($database) ? 'database' : 'table'
            ]));

            $queryBuilder = new QueryBuilder($database, $table);
        }

        public function testConstructorSuccess(): void {
            $database = 'db';
            $table = 'table';

            $queryBuilder = new QueryBuilder($database, $table);
            $this->assertEquals($database, $queryBuilder->getDatabase());
            $this->assertEquals($table, $queryBuilder->getTable());
        }

        public function GetPDOTypeFromValueProvider(): array {
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
         * @dataProvider GetPDOTypeFromValueProvider
         */

        public function testGetPDOTypeFromValue(
            $value,
            int $expected
        ): void {
            $queryBuilder = new QueryBuilder('db', 'table');
            $this->assertEquals($expected, $queryBuilder->getPDOTypeFromValue($value));
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
			$values = [
				'name' => 'Hadi Darwish',
				'email' => 'hadi@example.com',
				'age' => 22,
			];

			$queryBuilder = new QueryBuilder($db, $table);
			$queryBuilder->setData($values);
			[
				'sql' => $sql,
				'binds' => $binds
			] = $queryBuilder->insert();

			$expectedSql = "INSERT INTO `{$db}`.`{$table}` (`name`, `email`, `age`) VALUES (:name_1, :email_1, :age_1)";
			$expectedBinds = [];

			foreach ($values AS $column => $value) {
				$bind_key = ":{$column}_1";
				$expectedBinds[$bind_key] = [
					'value' => $value,
					'type' => QueryBuilder::GetPDOTypeFromValue($value)
				];
			}

			$this->assertEquals($expectedSql, $sql);
			$this->assertEqualsCanonicalizing($expectedBinds, $binds);
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

		// public function testDeleteNoDataToInsertThrows(): void {
		// 	$this->expectException(NotEmptyParamException::class);
		// 	$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
		// 		"::params::" => "whereData"
		// 	]));

		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->delete();
		// }

		// public function testDeleteSingleRecordSuccess(): void {
		// 	$db = 'db';
		// 	$table = 'table';
		// 	$where = [
		// 		'id' => 1,
		// 	];

		// 	$queryBuilder = new QueryBuilder($db, $table);
		// 	$queryBuilder->setWhere($where);
		// 	[
		// 		'sql' => $sql,
		// 		'binds' => $binds
		// 	] = $queryBuilder->delete(['id' => 1]);

		// 	$expectedSql = 'DELETE FROM db.table WHERE `id` = :id';
		// 	$expectedBinds = [
		// 		':id' => ['value' => 1, 'type' => 1],
		// 	];

		// 	$this->assertEquals($expectedSql, $sql);
		// 	$this->assertEqualsCanonicalizing($expectedBinds, $binds);
		// }

		// public function testSelectNoDataToSelectThrows(): void {
		// 	$this->expectException(NotEmptyParamException::class);
		// 	$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
		// 		"::params::" => "data"
		// 	]));

		// 	$queryBuilder = new QueryBuilder('db', 'table');
		// 	$queryBuilder->select();
		// }

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

	}
