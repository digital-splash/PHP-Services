<?php
	namespace OldCode\DigitalSplash\Tests\Database\MySQL\Models\Base;

	use OldCode\DigitalSplash\Database\MySQL\Models\Base\NonIndexedArray;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
	use PDO;
	use PHPUnit\Framework\TestCase;

	class NonIndexedArrayTest extends TestCase {

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

			new NonIndexedArray($implodeValue, $statementPrefix);
		}

		public function testFinalStringAllCases(): void {
			$nonIndexedArray = new NonIndexedArray(', ', 'SET');
			$this->assertEmpty($nonIndexedArray->getFinalString());

			$value = 'This is a final String';
			$nonIndexedArray->setFinalString($value);
			$this->assertEquals($value, $nonIndexedArray->getFinalString());

			$nonIndexedArray->clearFinalString();
			$this->assertEmpty($nonIndexedArray->getFinalString());
		}

		public function testArrayAllCases(): void {
			$nonIndexedArray = new NonIndexedArray(', ', 'SET');
			$this->assertCount(0, $nonIndexedArray->getArray());

			$value = [
				'value1',
				'value2',
				'value3',
			];
			$nonIndexedArray->setArray($value);
			$this->assertEqualsCanonicalizing($value, $nonIndexedArray->getArray());
			$this->assertCount(3, $nonIndexedArray->getArray());

			$value[] = 'value4';
			$nonIndexedArray->appendToArray('value4');
			$this->assertEqualsCanonicalizing($value, $nonIndexedArray->getArray());
			$this->assertCount(4, $nonIndexedArray->getArray());

			$nonIndexedArray->clearArray();
			$this->assertCount(0, $nonIndexedArray->getArray());

			$value = [
				'value5'
			];
			$nonIndexedArray->appendToArray('value5');
			$this->assertEqualsCanonicalizing($value, $nonIndexedArray->getArray());
			$this->assertCount(1, $nonIndexedArray->getArray());

			$values = [
				'value6',
				'value7',
				'value8'
			];
			$nonIndexedArray->appendArrayToArray($values);
			$this->assertEqualsCanonicalizing(array_merge($value, $values), $nonIndexedArray->getArray());
			$this->assertCount(4, $nonIndexedArray->getArray());
		}

		public function generateStringStatementProvider(): array {
			return [
				'empty_array' => [
					'array' => [],
					'expectedFinalString' => ''
				],
				'array_with_one_element' => [
					'array' => [
						'value1'
					],
					'expectedFinalString' => 'SET value1'
				],
				'array_with_multiple_elements' => [
					'array' => [
						'value1',
						'value2',
						'value3'
					],
					'expectedFinalString' => 'SET value1, value2, value3'
				]
			];
		}

		/**
		 * @dataProvider generateStringStatementProvider
		 */
		public function testGenerateStringStatement(
			array $array,
			string $expectFinalString
		): void {
			$nonIndexedArray = new NonIndexedArray(', ', 'SET');
			$nonIndexedArray->setArray($array);
			$nonIndexedArray->generateStringStatement();

			$this->assertEquals($expectFinalString, $nonIndexedArray->getFinalString());
		}

		public function testGenerateStringStatementWithBinds(): void {
			$nonIndexedArray = new NonIndexedArray(', ', 'SET');
			$nonIndexedArray->setArray([
				'value1 = :value1'
			]);
			$nonIndexedArray->generateStringStatement();
			$nonIndexedArray->binds->appendToBinds(':value1', 'value1');
			$this->assertEquals('SET value1 = :value1', $nonIndexedArray->getFinalString());
			$this->assertEqualsCanonicalizing([
					':value1' => [
						'value' => 'value1',
						'dataType' => PDO::PARAM_STR
					]
			], $nonIndexedArray->binds->getBinds());
		}

	}
