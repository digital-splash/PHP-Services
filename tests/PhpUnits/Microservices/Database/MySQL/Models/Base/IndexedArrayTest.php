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

		// ! IMPORTANT ask about the success test?? 

		public function testGetFinalString(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$this->assertEquals('', $indexedArray->getFinalString());
		}

		public function testSetFinalString(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$indexedArray->setFinalString('test');
			$this->assertEquals('test', $indexedArray->getFinalString());
		}

		public function testClearFinalString(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$indexedArray->setFinalString('test');
			$indexedArray->clearFinalString();
			$this->assertEquals('', $indexedArray->getFinalString());
		}
		//! ask if i should keep it canonicalizing
		public function testGetArray(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$this->assertEqualsCanonicalizing([], $indexedArray->getArray());
		}

		public function testSetArray(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$indexedArray->setArray(['test' => 'test']);
			$this->assertEqualsCanonicalizing(['test' => 'test'], $indexedArray->getArray());
		}

		public function testClearArray(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$indexedArray->setArray(['test' => 'test']);
			$indexedArray->clearArray();
			$this->assertEqualsCanonicalizing([], $indexedArray->getArray());
		}

		public function testAppendToArray(): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$indexedArray->appendToArray('test', 'test');
			$this->assertEqualsCanonicalizing(['test' => 'test'], $indexedArray->getArray());
		}

		public function generateStringStatementProvider(): array {
			return [
				'empty array' => [
					'array' => [],
					'expectFinalString' => '',
                    'expectBinds' => []
				],
				'array with one element' => [
					'array' => [
						'key1' => 'value1'
					],
					'expectFinalString' => 'SET `key1` = :key1',
                    'expectBinds' => [
                        ':key1' => [
                            'value' => 'value1',
                            'type' => PDO::PARAM_STR
                        ]
                    ]
				],
				'array with two elements' => [
					'array' => [
						'key1' => 'value1',
						'key2' => 'value2'
					],
					'expectFinalString' => 'SET `key1` = :key1, `key2` = :key2',
                    'expectBinds' => [
                        ':key1' => [
                            'value' => 'value1',
					        'type' => PDO::PARAM_STR
                        ],
                        ':key2' => [
                            'value' => 'value2',
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
			string $expectFinalString,
            array $expectBinds
		): void {
			$indexedArray = new IndexedArray(', ', 'SET');
			$indexedArray->setArray($array);
			$indexedArray->generateStringStatement();

			$this->assertEquals($expectFinalString, $indexedArray->getFinalString());
        
            $this->assertEqualsCanonicalizing($expectBinds, $indexedArray->binds->getBinds());
		}
	}