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

		public function testGetSql(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals('', $queryBuilder->getSql());
		}

		public function testSetSql(): void {
			$database = 'db';
			$table = 'table';
			$sql = 'SELECT * FROM `db`.`table`';

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setSql($sql);
			$this->assertEquals($sql, $queryBuilder->getSql());
		}

		public function testClearSql(): void {
			$database = 'db';
			$table = 'table';
			$sql = 'SELECT * FROM `db`.`table`';

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setSql($sql);
			$queryBuilder->clearSql();
			$this->assertEquals('', $queryBuilder->getSql());
		}

		public function testGetBinds(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals([], $queryBuilder->getBinds());
		}

		public function testSetBinds(): void {
			$database = 'db';
			$table = 'table';
			$binds = [
				':name' => 'Hadi Darwish',
				':email' => 'hadi@example.com',
				':age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setBinds($binds);
			$this->assertEquals($binds, $queryBuilder->getBinds());
		}

		public function testClearBinds(): void {
			$database = 'db';
			$table = 'table';
			$binds = [
				':name' => 'Hadi Darwish',
				':email' => 'hadi@example.com',
				':age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setBinds($binds);
			$queryBuilder->clearBinds();
			$this->assertEquals([], $queryBuilder->getBinds());
		}

		public function testAppendToBind(): void {
			$database = 'db';
			$table = 'table';
			$binds = [
				':name' => 'Hadi Darwish',
				':email' => 'hadi@example.com',
				':age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setBinds($binds);
			$queryBuilder->appendToBind(':address', 'Beirut');
			$binds[':address'] = 'Beirut';
			$this->assertEquals($binds, $queryBuilder->getBinds());
		}

		public function testGetData(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals([], $queryBuilder->getData());
		}

		public function testSetData(): void {
			$database = 'db';
			$table = 'table';
			$data = [
				'name' => 'Hadi Darwish',
				'email' => 'hadi@example.com',
				'age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setData($data);
			$this->assertEquals($data, $queryBuilder->getData());
		}

		public function testClearData(): void {
			$database = 'db';
			$table = 'table';
			$data = [
				'name' => 'Hadi Darwish',
				'email' => 'hadi@example.com',
				'age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setData($data);
			$queryBuilder->clearData();
			$this->assertEquals([], $queryBuilder->getData());
		}

		public function testAppendToData(): void {
			$database = 'db';
			$table = 'table';
			$data = [
				'name' => 'Hadi Darwish',
				'email' => 'hadi@example.com',
				'age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setData($data);
			$queryBuilder->appendToData('address', 'Beirut');
			$data['address'] = 'Beirut';
			$this->assertEquals($data, $queryBuilder->getData());
		}

		public function testGetWhere(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals([], $queryBuilder->getWhere());
		}

		public function testSetWhere(): void {
			$database = 'db';
			$table = 'table';
			$where = [
				'name' => 'Hadi Darwish',
				'email' => 'hadi@example.com',
				'age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setWhere($where);
			$this->assertEquals($where, $queryBuilder->getWhere());
		}

		public function testClearWhere(): void {
			$database = 'db';
			$table = 'table';
			$where = [
				'name' => 'Hadi Darwish',
				'email' => 'hadi@example.com',
				'age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setWhere($where);
			$queryBuilder->clearWhere();
			$this->assertEquals([], $queryBuilder->getWhere());
		}

		public function testAppendToWhere(): void {
			$database = 'db';
			$table = 'table';
			$where = [
				'name' => 'Hadi Darwish',
				'email' => 'hadi@example.com',
				'age' => 22,
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setWhere($where);
			$queryBuilder->appendToWhere('address', 'Beirut');
			$where['address'] = 'Beirut';
			$this->assertEquals($where, $queryBuilder->getWhere());
		}

		public function testGetJoin(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals([], $queryBuilder->getJoin());
		}

		public function testSetJoin(): void {
			$database = 'db';
			$table = 'table';
			$join = [
				'LEFT JOIN table1 ON table1.id = table.id',
				'RIGHT JOIN table2 ON table2.id = table.id'
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setJoin($join);
			$this->assertEquals($join, $queryBuilder->getJoin());
		}

		public function testClearJoin(): void {
			$database = 'db';
			$table = 'table';
			$join = [
				'LEFT JOIN table1 ON table1.id = table.id',
				'RIGHT JOIN table2 ON table2.id = table.id'
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setJoin($join);
			$queryBuilder->clearJoin();
			$this->assertEquals([], $queryBuilder->getJoin());
		}

		public function testAppendToJoin(): void {
			$database = 'db';
			$table = 'table';
			$join = [
				'LEFT JOIN table1 ON table1.id = table.id',
				'RIGHT JOIN table2 ON table2.id = table.id'
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setJoin($join);
			$queryBuilder->appendToJoin('INNER JOIN table3 ON table3.id = table.id');
			$join[] = 'INNER JOIN table3 ON table3.id = table.id';
			$this->assertEquals($join, $queryBuilder->getJoin());
		}

		public function testGetGroup(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals([], $queryBuilder->getGroup());
		}

		public function testSetGroup(): void {
			$database = 'db';
			$table = 'table';
			$group = ['name', 'email'];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setGroup($group);
			$this->assertEquals($group, $queryBuilder->getGroup());
		}

		public function testClearGroup(): void {
			$database = 'db';
			$table = 'table';
			$group = ['name', 'email'];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setGroup($group);
			$queryBuilder->clearGroup();
			$this->assertEquals([], $queryBuilder->getGroup());
		}

		public function testAppendToGroup(): void {
			$database = 'db';
			$table = 'table';
			$group = ['name', 'email'];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setGroup($group);
			$queryBuilder->appendToGroup('age');
			$group[] = 'age';
			$this->assertEquals($group, $queryBuilder->getGroup());
		}

		public function testGetHaving(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals([], $queryBuilder->getHaving());
		}

		public function testSetHaving(): void {
			$database = 'db';
			$table = 'table';
			$having = [
				'name = Hadi Darwish',
				'email = hadi@example.com',
				'age = 22',
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setHaving($having);
			$this->assertEquals($having, $queryBuilder->getHaving());
		}

		public function testClearHaving(): void {
			$database = 'db';
			$table = 'table';
			$having = [
				'name = Hadi Darwish',
				'email = hadi@example.com',
				'age = 22',
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setHaving($having);
			$queryBuilder->clearHaving();
			$this->assertEquals([], $queryBuilder->getHaving());
		}

		public function testAppendToHaving(): void {
			$database = 'db';
			$table = 'table';
			$having = [
				'name = Hadi Darwish',
				'email = hadi@example.com',
				'age = 22',
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setHaving($having);
			$queryBuilder->appendToHaving('age = 23');
			$having[] = 'age = 23';
			$this->assertEquals($having, $queryBuilder->getHaving());
		}

		public function testGetOrder(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals([], $queryBuilder->getOrder());
		}

		public function testSetOrder(): void {
			$database = 'db';
			$table = 'table';
			$order = [
				'name = ASC',
				'email = DESC',
				'age = ASC',
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setOrder($order);
			$this->assertEquals($order, $queryBuilder->getOrder());
		}

		public function testClearOrder(): void {
			$database = 'db';
			$table = 'table';
			$order = [
				'name = ASC',
				'email = DESC',
				'age = ASC',
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setOrder($order);
			$queryBuilder->clearOrder();
			$this->assertEquals([], $queryBuilder->getOrder());
		}

		public function testAppendToOrder(): void {
			$database = 'db';
			$table = 'table';
			$order = [
				'name = ASC',
				'email = DESC',
			];

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setOrder($order);
			$queryBuilder->appendToOrder('age = DESC');
			$order[] = 'age = DESC';
			$this->assertEquals($order, $queryBuilder->getOrder());
		}

		public function testGetLimit(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals(0, $queryBuilder->getLimit());
		}
		
		public function testSetLimit(): void {
			$database = 'db';
			$table = 'table';
			$limit = 10;

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setLimit($limit);
			$this->assertEquals($limit, $queryBuilder->getLimit());
		}

		public function testClearLimit(): void {
			$database = 'db';
			$table = 'table';
			$limit = 10;

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setLimit($limit);
			$queryBuilder->clearLimit();
			$this->assertEquals(0, $queryBuilder->getLimit());
		}

		public function testGetOffset(): void {
			$database = 'db';
			$table = 'table';

			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals(0, $queryBuilder->getOffset());
		}

		public function testSetOffset(): void {
			$database = 'db';
			$table = 'table';
			$offset = 10;

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setOffset($offset);
			$this->assertEquals($offset, $queryBuilder->getOffset());
		}

		public function testClearOffset(): void {
			$database = 'db';
			$table = 'table';
			$offset = 10;

			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setOffset($offset);
			$queryBuilder->clearOffset();
			$this->assertEquals(0, $queryBuilder->getOffset());
		}

		public function testGetWhereStr() : void {
			$database = 'db';
			$table = 'table';
			
			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals('', $queryBuilder->getWhereStr());
		}

		public function testSetWhereStr() : void {
			$database = 'db';
			$table = 'table';
			$whereStr = ' WHERE name = Hadi Darwish';
			
			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setWhereStr($whereStr);
			$this->assertEquals($whereStr, $queryBuilder->getWhereStr());
		}

		public function testClearWhereStr() : void {
			$database = 'db';
			$table = 'table';
			$whereStr = ' WHERE name = Hadi Darwish';
			
			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setWhereStr($whereStr);
			$queryBuilder->clearWhereStr();
			$this->assertEquals('', $queryBuilder->getWhereStr());
		}

		public function testGetJoinStr() : void {
			$database = 'db';
			$table = 'table';
			
			$queryBuilder = new QueryBuilder($database, $table);
			$this->assertEquals('', $queryBuilder->getJoinStr());
		}

		public function testSetJoinStr() : void {
			$database = 'db';
			$table = 'table';
			$joinStr = ' JOIN table2 ON table.id = table2.id';
			
			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setJoinStr($joinStr);
			$this->assertEquals($joinStr, $queryBuilder->getJoinStr());
		}

		public function testClearJoinStr() : void {
			$database = 'db';
			$table = 'table';
			$joinStr = ' JOIN table2 ON table.id = table2.id';
			
			$queryBuilder = new QueryBuilder($database, $table);
			$queryBuilder->setJoinStr($joinStr);
			$queryBuilder->clearJoinStr();
			$this->assertEquals('', $queryBuilder->getJoinStr());
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
			$queryBuilder->setData($data);
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
			$queryBuilder->setData($data);
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

		public function getWhereStatementProvider() : array {
			return [
				'empty where' => [
					'where' => [],
					'expectedSql' => '',
					'expectedBinds' => []
				],
				'where with one column' => [
					'where' => [
						'id' => 1
					],
					'expectedSql' => ' WHERE `id` = :id',
					'expectedBinds' => [
						':id' => [
							'value' => 1,
							'type' => 1
						]
					]
				],
				'where with many columns' => [
					'where' => [
						'id' => 1,
						'name' => 'test',
						'age' => 2
					],
					'expectedSql' => ' WHERE `id` = :id AND `name` = :name AND `age` = :age',
					'expectedBinds' => [
						':id' => [
							'value' => 1,
							'type' => 1
						],
						':name' => [
							'value' => 'test',
							'type' => 2
						],
						':age' => [
							'value' => 2,
							'type' => 1
						]
					]
				],
			];
					
		}

		/**
		 * @dataProvider getWhereStatementProvider
		 */
		public function testGetWhereStatementSuccess(array $where, string $expectedSql, array $expectedBinds) : void {
			$queryBuilder = new QueryBuilder('db', 'table');
			$queryBuilder->setWhere($where);
			$queryBuilder->getWhereStatement();

			$this->assertEquals($expectedSql, $queryBuilder->getWhereStr());
			$this->assertEquals($expectedBinds, $queryBuilder->getBinds());
			
		}

		public function getJoinStatementProvider() : array {
			return [
				'empty join' => [
					'join' => [],
					'expectedSql' => '',
				],
				'join with one column' => [
					['LEFT JOIN db.table2 ON table.id = table2.id'],
					'expectedSql' => 'LEFT JOIN db.table2 ON table.id = table2.id',
				],
				'join with many columns' => [
					'join' => [
						'LEFT JOIN db.table2 ON table.id = table2.id',
						'RIGHT JOIN db.table3 ON table.id = table3.id',
					],
					'expectedSql' => 'LEFT JOIN db.table2 ON table.id = table2.id, RIGHT JOIN db.table3 ON table.id = table3.id',
				],
			];
					
		}

		/**
		 * @dataProvider getJoinStatementProvider
		 */
		public function testGetJoinStatementSuccess(array $join, string $expectedSql) : void {
			$queryBuilder = new QueryBuilder('db', 'table');
			$queryBuilder->setJoin($join);
			$queryBuilder->getJoinStatement();

			$this->assertEquals($expectedSql, $queryBuilder->getJoinStr());
			
		}


	}
