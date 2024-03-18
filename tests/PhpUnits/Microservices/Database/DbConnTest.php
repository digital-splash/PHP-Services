<?php
	namespace DigitalSplash\Tests\Database;

	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Language\Helpers\Translate;
	use Exception;
	use DigitalSplash\Database\DbConn;
	use DigitalSplash\Database\QueryAttributes\Condition;
	use DigitalSplash\Database\QueryAttributes\Join;
	use DigitalSplash\Database\QueryAttributes\Order;
	use DigitalSplash\Tests\Utils\DbTestUtils;
	use PHPUnit\Framework\TestCase;

	class DbConnTest extends TestCase {

		public static function setUpBeforeClass(): void {
			DbConn::executeRawQueryStatic("CREATE TABLE IF NOT EXISTS `test` (
				`Id` int(11) NOT NULL AUTO_INCREMENT,
				`Title` varchar(255) NOT NULL,
				`Active` tinyint(1) NOT NULL DEFAULT '1',
				`Deleted` tinyint(1) NOT NULL DEFAULT '0',
				`DeletedDate` datetime DEFAULT NULL,
				`CreatedOn` datetime DEFAULT NULL,
				`LastUpdated` datetime DEFAULT NULL,
				PRIMARY KEY (`Id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

			DbConn::executeRawQueryStatic("CREATE TABLE IF NOT EXISTS `test2` (
				`Id` int(11) NOT NULL AUTO_INCREMENT,
				`Title` varchar(255) NOT NULL,
				PRIMARY KEY (`Id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

			parent::setUpBeforeClass();
		}

		public static function tearDownAfterClass(): void {
			DbConn::executeRawQueryStatic("DROP TABLE IF EXISTS `test`");
			DbConn::executeRawQueryStatic("DROP TABLE IF EXISTS `test2`");

			parent::tearDownAfterClass();
		}

		public function setUp(): void {
			self::cleanUp();

			parent::setUp();
		}

		public function tearDown(): void {
			self::cleanUp();

			parent::tearDown();
		}

		private static function cleanUp(): void {
			DbTestUtils::truncateTable('test');
			DbTestUtils::truncateTable('test2');
		}

		private static function insertDummyData(): array {
			$values = [
				[
					'Title' => 'Title 1',
					'Active' => 1,
					'Deleted' => 0,
					'DeletedDate' => null,
				],
				[
					'Title' => 'Title 2',
					'Active' => 1,
					'Deleted' => 0,
					'DeletedDate' => null,
	   			]
			];
			TestController::insertBulk($values);

			$values2 = [
				[
					'Title' => 'Title 2.1'
				],
				[
					'Title' => 'Title 2.2'
	   			]
			];
			Test2Controller::insertBulk($values2);

			return [
				'test' => $values,
				'test2' => $values2,
			];
		}

		public function testLoadFromControllerSuccess(): void {
			$test = new TestController();
			[
				'Id' => $id
			] = $test->save([
				'Title' => 'Title 001'
			]);

			$testFind = new TestController($id);
			$this->assertEquals($id, $testFind->row['Id']);
			$this->assertEquals('Title 001', $testFind->row['Title']);
			$this->assertNotEmpty($testFind->row['CreatedOn']);
			$this->assertEmpty($testFind->row['LastUpdated']);
		}

		public function testSaveWithCreatedOnSuccess(): void {
			$test = new TestController();
			$row = $test->save([
				'Title' => 'Title 001'
			]);
			$createdOn = $row['CreatedOn'];

			$this->assertNotEmpty($createdOn);
			$this->assertEmpty($row['LastUpdated']);

			$row = $test->save([
				'Title' => 'Title 002'
			]);
			$this->assertEquals($createdOn, $row['CreatedOn']);
			$this->assertEquals('Title 002', $row['Title']);
			$this->assertNotEmpty($row['LastUpdated']);

			$test->Title = 'Title 003';
			$row = $test->save();
			$this->assertEquals('Title 003', $row['Title']);
		}

		public function testSaveWithoutCreatedOnSuccess(): void {
			$test = new Test2Controller();
			$row = $test->save([
				'Title' => 'Title 001'
			]);

			$this->assertArrayNotHasKey('CreatedOn', $row);
			$this->assertArrayNotHasKey('LastUpdated', $row);

			$row = $test->save([
				'Title' => 'Title 002'
			]);
			$this->assertEquals('Title 002', $row['Title']);
		}

		public function testInsertBulkException(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "values"
			]));

			TestController::insertBulk([]);
		}

		/**
		 * @dataProvider insertBulkSuccessProvider
		 */
		public function testInsertBulkSuccess(array $values): void {
			TestController::insertBulk($values);

			foreach($values as $valuesRow) {
				$dbObj = new TestController();
				$dbObj->filterDeletedClear();
				$dbValue = $dbObj->getByKeyValue('Title', $valuesRow['Title']);

				foreach ($valuesRow as $key => $value) {
					$this->assertEquals($value, $dbValue[0][$key]);
				}
			}

			$objAll = new TestController();
			$objAll->filterDeletedClear();
			$data = $objAll->selectFromDB();

			$this->assertEquals(count($values), count($data));
		}

		public function insertBulkSuccessProvider(): array {
			return [
				'single_record' => [
					'values' => [
						[
							'Title' => 'Single Record Title',
							'Active' => 1,
							'Deleted' => 0,
						]
					]
				],
				'multiple_records' => [
					'values' => [
						[
							'Title' => 'Multiple Records 1',
							'Active' => 1,
							'Deleted' => 0,
						],
						[
							'Title' => 'Multiple Records 2',
							'Active' => 1,
							'Deleted' => 1,
						],
						[
							'Title' => 'Multiple Records 3',
							'Active' => 0,
							'Deleted' => 0,
						]
					]
				]
			];
		}

		/**
		 * @dataProvider updateBulkExceptionProvider
		 */
		public function testUpdateBulkException(
			array $values,
			string $expection,
			string $expectionMessage
		): void {
			$this->expectException($expection);
			$this->expectExceptionMessage($expectionMessage);

			TestController::updateBulk($values);
		}

		public function updateBulkExceptionProvider(): array {
			return [
				'empty_values' => [
					'values' => [],
					'expection' => NotEmptyParamException::class,
					'expectionMessage' => Translate::TranslateString("exception.NotEmptyParam", null, [
						"::params::" => "values"
					])
				],
				'column_not_exist' => [
					'values' => [
						[
							'Title' => 'Test 1',
							'Active' => 1,
							'Deleted' => 0
						]
					],
					'expection' => Exception::class,
					'expectionMessage' => 'Column Id is not in one of the values'
				]
			];
		}

		/**
		 * @dataProvider updateBulkSuccessProvider
		 */
		public function testUpdateBulkSuccess(
			array $values,
			array $valuesToUpdate,
			string $column
		): void {
			TestController::insertBulk($values);
			TestController::updateBulk($valuesToUpdate, $column);

			foreach($valuesToUpdate as $valuesRow) {
				$dbObj = new TestController();
				$dbObj->filterDeletedClear();
				$dbValue = $dbObj->getByKeyValue($column, $valuesRow[$column]);

				foreach ($valuesRow as $key => $value) {
					$this->assertEquals($value, $dbValue[0][$key]);
				}
			}

			$objAll = new TestController();
			$objAll->filterDeletedClear();
			$data = $objAll->selectFromDB();

			$this->assertEquals(count($values), count($data));
		}

		public function updateBulkSuccessProvider(): array {
			return [
				'single_record' => [
					'values' => [
						[
							'Title' => 'Test Single',
							'Active' => 1,
							'Deleted' => 0,
						]
					],
					'valuesToUpdate' => [
						[
							'Title' => 'Test Single',
							'Active' => 0
						]
					],
					'column' => 'Title',
					// 'query to compare' => "UPDATE `test` SET `Active` = (CASE WHEN `Title` = 'Test 3' THEN '0' \nELSE `Active` END), `Deleted` = (CASE WHEN `Title` = 'Test 3' THEN '1' \nELSE `Deleted` END), `DeletionDate` = (CASE WHEN `Title` = 'Test 3' THEN '2020-01-01 00:00:00' \nELSE `DeletionDate` END), `CreatedBy` = (CASE WHEN `Title` = 'Test 3' THEN '2' \nELSE `CreatedBy` END), `CreatedType` = (CASE WHEN `Title` = 'Test 3' THEN 'Ad Updated' \nELSE `CreatedType` END), `LastUpdated` = (CASE WHEN `Title` = 'Test 3' THEN '2023-10-17 23:51:08' \nELSE `LastUpdated` END) WHERE `Title` IN(\"Test 3\");"
				],
				'multiple_records' => [
					'values' => [
						[
							'Title' => 'Test Multiple 1',
							'Active' => 1,
							'Deleted' => 0,
							'DeletedDate' => null
						],
						[
							'Title' => 'Test Multiple 2',
							'Active' => 0,
							'Deleted' => 1,
							'DeletedDate' => '2020-01-01 00:00:00',
						]
					],
					'valuesToUpdate' => [
						[
							'Title' => 'Test Multiple 1',
							'Active' => 0,
							'Deleted' => 1,
							'DeletedDate' => '2020-01-01 00:00:00',
						],
						[
							'Title' => 'Test Multiple 2',
							'Active' => 1,
							'Deleted' => 0,
							'DeletedDate' => null,
						]
					],
					'column' => 'Title',
					// 'queryToCompare' => "UPDATE `test` SET `Active` = (CASE WHEN `Title` = 'Test 1' THEN '0' \nWHEN `Title` = 'Test 2' THEN '0' \nELSE `Active` END), `Deleted` = (CASE WHEN `Title` = 'Test 1' THEN '1' \nWHEN `Title` = 'Test 2' THEN '1' \nELSE `Deleted` END), `DeletionDate` = (CASE WHEN `Title` = 'Test 1' THEN '2020-01-01 00:00:00' \nWHEN `Title` = 'Test 2' THEN '2020-01-01 00:00:00' \nELSE `DeletionDate` END), `CreatedBy` = (CASE WHEN `Title` = 'Test 1' THEN '2' \nWHEN `Title` = 'Test 2' THEN '2' \nELSE `CreatedBy` END), `CreatedType` = (CASE WHEN `Title` = 'Test 1' THEN 'Ad Updated' \nWHEN `Title` = 'Test 2' THEN 'Ad Updated' \nELSE `CreatedType` END), `LastUpdated` = (CASE WHEN `Title` = 'Test 1' THEN '2023-10-17 23:46:24' \n WHEN `Title` = 'Test 2' THEN '2023-10-17 23:46:24' \nELSE `LastUpdated` END) WHERE `Title` IN(\"Test 1\",\"Test 2\");"
				]
			];
		}

		/**
		 * @param string[] $fields
		 * @param Condition[] $conditions
		 * @param Join[] $join
		 * @param string $groupBy
		 * @param Condition[] $having
		 * @param Order[] $orderBy
		 * @param int $limit
		 * @param int $offset
		 * @dataProvider selectFromDBSuccessProvider
		 */
		public function testSelectFromDBWithParams(
			array $fields = null,
			array $condition = null,
			array $join = null,
			string $groupBy = null,
			array $having = null,
			array $orderBy = null,
			int $limit = null,
			int $offset = null,
			int $expectedCount = null,
			string $expectedQuery
		): void {
			self::insertDummyData();

			$object = new TestController();
			$object->filterDeletedClear();
			$object->setFields($fields);
			if(!Helper::IsNullOrEmpty($condition)) {
				$object->setConditions($condition);
			}
			if(!Helper::IsNullOrEmpty($join)) {
				$object->setJoin($join);
			}
			if(!Helper::IsNullOrEmpty($groupBy)) {
				$object->setGroupBy($groupBy);
			}
			if(!Helper::IsNullOrEmpty($having)) {
				$object->setHaving($having);
			}
			if(!Helper::IsNullOrEmpty($orderBy)) {
				$object->setOrderBy($orderBy);
			}
			if($limit != 0 && !Helper::IsNullOrEmpty($limit)) {
				$object->setLimit($limit);
			}
			if($offset != 0 && !Helper::IsNullOrEmpty($offset)) {
				$object->setOffset($offset);
			}
			$data = $object->selectFromDB();

			$this->assertCount($expectedCount, $data);

			$query = $object->getQuery()->toSql();
			$this->assertEquals($expectedQuery, $query);
		}

		public function selectFromDBSuccessProvider(): array {
			return [
				'select_all_fields' => [
					'fields' => [],
					'condition' => [],
					'join' => [],
					'groupBy' => '',
					'having' => [],
					'orderBy' => [],
					'limit' => 0,
					'offset' => 0,
					'expectedCount' => 2,
					'expectedQuery' => 'select * from `test`'
				],
				'select_columns' => [
					'fields' => ['Id', 'Title'],
					'condition' => [],
					'join' => [],
					'groupBy' => '',
					'having' => [],
					'orderBy' => [],
					'limit' => 0,
					'offset' => 0,
					'expectedCount' => 2,
					'expectedQuery' => 'select `Id`, `Title` from `test`'
				],
				'select_condition' => [
					'fields' => [],
					'condition' => [
						new Condition('Title', 'Title 1', '!=')
					],
					'join' => [],
					'groupBy' => '',
					'having' => [],
					'orderBy' => [],
					'limit' => 0,
					'offset' => 0,
					'expectedCount' => 1,
					'expectedQuery' => 'select * from `test` where `Title` != ?'
				],
				'select_join' => [
					'fields' => [],
					'condition' => [],
					'join' => [
						new Join('test2', 'test.Id', 'test2.Id', 'inner')
					],
					'groupBy' => '',
					'having' => [],
					'orderBy' => [],
					'limit' => 0,
					'offset' => 0,
					'expectedCount' => 2,
					'expectedQuery' => 'select * from `test` inner join `test2` on `test`.`Id` = `test2`.`Id`'
				],
				'select_group' => [
					'fields' => [],
					'condition' => [],
					'join' => [],
					'groupBy' => 'Id',
					'having' => [],
					'orderBy' => [],
					'limit' => 0,
					'offset' => 0,
					'expectedCount' => 2,
					'expectedQuery' => 'select * from `test` group by Id'
				],
				'select_having' => [
					'fields' => [],
					'condition' => [],
					'join' => [],
					'groupBy' => '',
					'having' => [
						new Condition('Id', 1, '>')
					],
					'orderBy' => [],
					'limit' => 0,
					'offset' => 0,
					'expectedCount' => 1,
					'expectedQuery' => 'select * from `test` having `Id` > ?'
				],
				'select_order' => [
					'fields' => [],
					'condition' => [],
					'join' => [],
					'groupBy' => '',
					'having' => [],
					'orderBy' => [
						new Order('Id', 'desc')
					],
					'limit' => 0,
					'offset' => 0,
					'expectedCount' => 2,
					'queryToCompare' => 'select * from `test` order by `Id` desc'
				],
				'select_limit' => [
					'fields' => [],
					'condition' => [],
					'join' => [],
					'groupBy' => '',
					'having' => [],
					'orderBy' => [],
					'limit' => 2,
					'offset' => 0,
					'expectedCount' => 2,
					'queryToCompare' => 'select * from `test` limit 2'
				],
				'select_offset' => [
					'fields' => [],
					'condition' => [],
					'join' => [],
					'groupBy' => '',
					'having' => [],
					'orderBy' => [],
					'limit' => 0,
					'offset' => 2,
					'expectedCount' => 2,
					'queryToCompare' => 'select * from `test`'
				],
				'select_limit_offset' => [
					'fields' => [],
					'condition' => [],
					'join' => [],
					'groupBy' => '',
					'having' => [],
					'orderBy' => [],
					'limit' => 1,
					'offset' => 1,
					'expectedCount' => 1,
					'queryToCompare' => 'select * from `test` limit 1 offset 1'
				],
				'select_complicated' => [
					'fields' => ['test.Id', 'test.Title'],
					'condition' => [
						new Condition('test.Title', 'Title 0', '!=')
					],
					'join' => [
						new Join('test2', 'test.Id', '=', 'test2.Id', 'left'),
					],
					'groupBy' => 'test.Id',
					'having' => [
						new Condition('test.Id', 0, '>')
					],
					'orderBy' => [
						new Order('test.Id', 'desc')
					],
					'limit' => 2,
					'offset' => 0,
					'expectedCount' => 2,
					'queryToCompare' => 'select `test`.`Id`, `test`.`Title` from `test` left join `test2` on `test`.`Id` = `test2`.`Id` where `test`.`Title` != ? group by test.Id having `test`.`Id` > ? order by `test`.`Id` desc limit 2'
				]
			];
		}

		public function testSelectFromDBFilterDeleted(): void {
			$values = [
				[
					'Title' => 'Title 1',
					'Active' => 1,
					'Deleted' => 0,
					'DeletedDate' => null,
				],
				[
					'Title' => 'Title 2',
					'Active' => 1,
					'Deleted' => 0,
					'DeletedDate' => null,
				],
				[
					'Title' => 'Title 3',
					'Active' => 1,
					'Deleted' => 1,
					'DeletedDate' => null,
				],
				[
					'Title' => 'Title 4',
					'Active' => 1,
					'Deleted' => 0,
					'DeletedDate' => null,
				],
				[
					'Title' => 'Title 5',
					'Active' => 1,
					'Deleted' => 1,
					'DeletedDate' => null,
				]
			];
			TestController::insertBulk($values);

			$object = new TestController();
			$object->filterDeletedClear();
			$data = $object->selectFromDB();
			$this->assertCount(5, $data);

			$object->clear();
			$object->filterOnlyDeleted();
			$data = $object->selectFromDB();
			$this->assertCount(2, $data);

			$object->clear();
			$object->filterOnlyNonDeleted();
			$data = $object->selectFromDB();
			$this->assertCount(3, $data);

			$object->clear();
			$object->addCondition(new Condition('Deleted', 0));
			$object->filterOnlyDeleted();
			$data = $object->selectFromDB();
			$this->assertCount(3, $data);
		}

		public function testSoftDeleteSuccess(): void {
			self::insertDummyData();

			$object = new TestController(1);
			$object->softDelete();

			$object->filterDeletedClear();
			$data = $object->getByKeyValue('Id','1');

			$this->assertEquals(1, $data[0]['Deleted']);
			$this->assertNotNull($data[0]['DeletedDate']);
		}

		/**
		 * @param Condition[] $conditions
		 * @dataProvider softDeleteAllProvider
		 */
		public function testSoftDeleteAll(
			array $conditions,
			int $expected_count
		): void {
			self::insertDummyData();

			TestController::softDeleteAll($conditions);

			$test = new TestController();
			$test->addCondition(new Condition('Deleted', 1));
			$rows = $test->selectFromDB();

			$this->assertCount($expected_count, $rows);
		}

		public function softDeleteAllProvider(): array {
			return [
				'no_condition' => [
					'conditions' => [],
					'expected_count' => 2
				],
				'with_condition_1' => [
					'conditions' => [
						new Condition('Title','Title 1','!='),
					],
					'expected_count' => 1
				],
				'with_condition_2' => [
					'conditions' => [
						new Condition('Title','Title 1','='),
					],
					'expected_count' => 1
				],
				'with_condition_3' => [
					'conditions' => [
						new Condition('Title','Title 0','!='),
					],
					'expected_count' => 2
				],
				'with_condition_4' => [
					'conditions' => [
						new Condition('Title','Title 0','='),
					],
					'expected_count' => 0
				],
			];
		}

		public function testRestoreSuccess(): void {
			self::insertDummyData();

			$object = new TestController(1);
			$object->softDelete();
			$object->restore();

			$data = $object->getByKeyValue('Id','1');

			$this->assertEquals(0, $data[0]['Deleted']);
			$this->assertNull($data[0]['DeletedDate']);
		}

		/**
		 * @param Condition[] $conditions
		 * @dataProvider restoreAllProvider
		 */
		public function testRestoreAll(
			array $conditions,
			int $expected_count
		): void {
			self::insertDummyData();
			TestController::softDeleteAll();

			TestController::restoreAll($conditions);

			$test = new TestController();
			$test->addCondition(new Condition('Deleted', 0));
			$rows = $test->selectFromDB();

			$this->assertCount($expected_count, $rows);
		}

		public function restoreAllProvider(): array {
			return [
				'no_condition' => [
					'conditions' => [],
					'expected_count' => 2
				],
				'with_condition_1' => [
					'conditions' => [
						new Condition('Title','Title 1','!='),
					],
					'expected_count' => 1
				],
				'with_condition_2' => [
					'conditions' => [
						new Condition('Title','Title 1','='),
					],
					'expected_count' => 1
				],
				'with_condition_3' => [
					'conditions' => [
						new Condition('Title','Title 0','!='),
					],
					'expected_count' => 2
				],
				'with_condition_4' => [
					'conditions' => [
						new Condition('Title','Title 0','='),
					],
					'expected_count' => 0
				],
			];
		}

		public function testHardDeleteSingleSuccess(): void {
			self::insertDummyData();

			$object = new TestController(1);
			$object->hardDelete();

			$data = $object->getByKeyValue('Id','1');

			$this->assertEmpty($data);

			$object = new TestController();
			$dataLeft = $object->selectFromDB();
			$this->assertCount(1, $dataLeft);
		}

		public function testHardDeleteMultipleSuccess(): void {
			self::insertDummyData();

			$object = new TestController();
			$object->selectFromDB();
			$object->hardDelete();

			$object = new TestController();
			$dataLeft = $object->selectFromDB();
			$this->assertEmpty($dataLeft);
		}

		/**
		 * @param Condition[] $conditions
		 * @dataProvider hardDeleteAllProvider
		 */
		public function testHardDeleteAll(
			array $conditions,
			int $expected_count
		): void {
			self::insertDummyData();

			TestController::hardDeleteAll($conditions);

			$test = new TestController();
			$rows = $test->selectFromDB();

			$this->assertCount($expected_count, $rows);
		}

		public function hardDeleteAllProvider(): array {
			return [
				'no_condition' => [
					'conditions' => [],
					'expected_count' => 0
				],
				'with_condition_1' => [
					'conditions' => [
						new Condition('Title','Title 1','!='),
					],
					'expected_count' => 1
				],
				'with_condition_2' => [
					'conditions' => [
						new Condition('Title','Title 1','='),
					],
					'expected_count' => 1
				],
				'with_condition_3' => [
					'conditions' => [
						new Condition('Title','Title 0','!='),
					],
					'expected_count' => 0
				],
				'with_condition_4' => [
					'conditions' => [
						new Condition('Title','Title 0','='),
					],
					'expected_count' => 2
				],
			];
		}

		public function testGetByKeyValue(): void {
			[
				'test' => $values
			] = self::insertDummyData();

			$object = new TestController();

			$result = $object->getByKeyValue('Title', 'Title 1');
			$this->assertEquals(1, count($result));

			foreach ($values[0] as $k => $v) {
				$this->assertEquals($v, $result[0][$k]);
			}

			$result = $object->getByKeyValue('Title', 'Title 2');
			$this->assertEquals(1, count($result));

			foreach ($values[1] as $k => $v) {
				$this->assertEquals($v, $result[0][$k]);
			}
		}

		public function testGetMaxId(): void {
			[
				'test' => $values
			] = self::insertDummyData();

			$maxId = TestController::getMaxId();

			$this->assertEquals(count($values), $maxId);
		}

		public function testGetMaxRow(): void {
			[
				'test' => $values
			] = self::insertDummyData();

			$row = TestController::getMaxRow();
			$lastIndex = count($values) - 1;

			foreach ($values[$lastIndex] as $k => $v) {
				$this->assertEquals($v, $row[$k]);
			}
		}

		public function testGetCountAll(): void {
			self::insertDummyData();

			$test = new TestController();
			$test->setLimit(1);
			$data = $test->selectFromDB();

			$this->assertCount(1, $data);

			$count = $test->getCountAll();
			$this->assertEquals(2, $count);
		}

		/**
		 * @dataProvider checkAvailabilityFromArrayProvider
		 */
		public function testCheckAvailabilityFromArray(array $values, bool $expected): void {
			self::insertDummyData();

			$test = new TestController();
			$res = $test->checkAvailabilityFromArray($values);

			$this->assertEquals($expected, $res);
		}

		public function checkAvailabilityFromArrayProvider(): array {
			return [
				'check_availability_true' => [
					'values' => [
						'Title' => 'Title 1',
						'Active' => 1,
					],
					true
				],
				'check_availability_false' => [
					'values' => [
						'Title' => 'notAvail',
						'Active' => 1,
					],
					false
				],
			];
		}

		/**
		 * @dataProvider checkAvailabilityProvider
		 */
		public function testCheckAvailability(string $value, $column, bool $expected): void {
			self::insertDummyData();

			$test = new TestController();
			$res = $test->checkAvailability($value, $column);

			$this->assertEquals($expected, $res);

		}

		public function checkAvailabilityProvider(): array {
			return [
				'check_availability_true' => [
					'value' => 'Title 1',
					'column' => 'Title',
					true
				],
				'check_availability_false' => [
					'value' => 'notAvail',
					'column' => 'Title',
					false
				],
			];
		}

		/**
		 * @dataProvider generateUniqueKeyProvider
		 */
		public function testGenerateUniqueKey(
			string $value,
			string $column,
			string $expected,
			string $separator = null
		): void {
			self::insertDummyData();

			$test = new TestController();
			if (!Helper::IsNullOrEmpty($separator)) {
				$key = $test->generateUniqueKey($value, $column, $separator);
			}
			else {
				$key = $test->generateUniqueKey($value, $column);
			}

			$this->assertEquals($expected, $key);
		}

		public function generateUniqueKeyProvider(): array {
			return [
				'generate_unique_key' => [
					'value' => 'Title 1',
					'column' => 'Title',
					'expected' => 'Title 1-1'
				],
				'generate_unique_key_with_separator' => [
					'value' => 'Title 1',
					'column' => 'Title',
					'expected' => 'Title 1_1',
					'separator' => '_'
				],
			];
		}

		public function testArabicCharsSuccess(): void {
			$test = new TestController();
			[
				'Title' => $title
			] = $test->save([
				'Title' => 'ماريو'
			]);

			$this->assertEquals('ماريو', $title);
		}
	}

	class TestController extends DbConn {
		protected $table = 'test';
		protected $primaryKey = 'Id';

		public $timestamps = false;

		protected $hidden = [];
		protected $fillable = [
			'Title',
			'Active',
			'Deleted',
			'DeletedDate',
			'CreatedOn',
			'LastUpdated'
		];
	}

	class Test2Controller extends DbConn {
		protected $table = 'test2';
		protected $primaryKey = 'Id';

		public $timestamps = false;

		protected $hidden = [];
		protected $fillable = [
			'Title'
		];
	}
