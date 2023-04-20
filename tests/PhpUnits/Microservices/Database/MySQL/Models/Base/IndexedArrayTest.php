<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models\Base;

	use DigitalSplash\Database\MySQL\Models\Base\IndexedArray;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
	use PDO;
	use PHPUnit\Framework\TestCase;

	class IndexedArrayTest extends TestCase {

		public function constructorThrowsProvider(): array {
			return [
				'empty implodeValue' => [
					'implodeValue' => '',
					'statementPrefix' => 'test',
					'expectExeption' => NotEmptyParamException::class,
					'expectExeptionMessage' => Translate::TranslateString("exception.NotEmptyParam", null, [
						"::params::" => 'implodeValue'
					])
				]
			];
		}

		/**
		 * @dataProvider constructorThrowsProvider
		 */
		public function testConstructorThrows(
			string $implodeValue,
			string $statementPrefix,
			string $expectExeption,
			string $expectExeptionMessage
		): void {
			$this->expectException($expectExeption);
			$this->expectExceptionMessage($expectExeptionMessage);

			new IndexedArray($implodeValue, $statementPrefix);
		}

		public function testFinalStringAllCases(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$this->assertEmpty($indexedArray->getFinalString());

			$value = 'This is a final String';
			$indexedArray->setFinalString($value);
			$this->assertEquals($value, $indexedArray->getFinalString());

			$indexedArray->clearFinalString();
			$this->assertEmpty($indexedArray->getFinalString());
		}

		public function testArrayAllCases(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$this->assertCount(0, $indexedArray->getArray());

			$value = [
				'key1' => 'value1',
				'key2' => 'value2',
				'key3' => 'value3',
			];
			$indexedArray->setArray($value);
			$this->assertEqualsCanonicalizing($value, $indexedArray->getArray());
			$this->assertCount(3, $indexedArray->getArray());

			$value['key4'] = 'value4';
			$indexedArray->appendToArray('key4', 'value4');
			$this->assertEqualsCanonicalizing($value, $indexedArray->getArray());
			$this->assertCount(4, $indexedArray->getArray());

			$indexedArray->clearArray();
			$this->assertCount(0, $indexedArray->getArray());

			$value = [
				'key5' => 'value5'
			];
			$indexedArray->appendToArray('key5', 'value5');
			$this->assertEqualsCanonicalizing($value, $indexedArray->getArray());
			$this->assertCount(1, $indexedArray->getArray());

            $values = [
                'key6' => 'value6',
                'key7' => 'value7',
                'key8' => 'value8',
            ];
            $indexedArray->appendArrayToArray($values);
            $this->assertEqualsCanonicalizing(array_merge($value, $values), $indexedArray->getArray());
            $this->assertCount(4, $indexedArray->getArray());
		}

		public function generateStringStatementProvider(): array {
			return [
				'empty_array' => [
					'array' => [],
					'expectedFinalString' => '',
					'expectedBinds' => []
				],
				'array_with_one_element' => [
					'array' => [
						'key1' => 'value1'
					],
					'expectedFinalString' => 'SET `key1` = :key1',
					'expectedBinds' => [
						':key1' => [
							'value' => 'value1',
							'type' => PDO::PARAM_STR
						]
					]
				],
				'array_with_multiple_elements' => [
					'array' => [
						'key1' => 1,
						'key2' => 'value2',
						'key3' => '2023-01-01',
					],
					'expectedFinalString' => 'SET `key1` = :key1, `key2` = :key2, `key3` = :key3',
					'expectedBinds' => [
						':key1' => [
							'value' => 1,
							'type' => PDO::PARAM_INT
						],
						':key2' => [
							'value' => 'value2',
							'type' => PDO::PARAM_STR
						],
						':key3' => [
							'value' => '2023-01-01',
							'type' => PDO::PARAM_STR
						]
					]
				]
			];
		}

		/**
		 * @dataProvider generateStringStatementProvider
		 */
		public function testGenerateStringStatement(
			array $array,
			string $expectedFinalString,
			array $expectedBinds
		): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$indexedArray->setArray($array);
			$indexedArray->generateStringStatement();

			$this->assertEquals($expectedFinalString, $indexedArray->getFinalString());
			$this->assertEqualsCanonicalizing($expectedBinds, $indexedArray->binds->getBinds());
		}

	}
