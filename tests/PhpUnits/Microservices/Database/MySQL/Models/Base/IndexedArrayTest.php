<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models\Base;

	use DigitalSplash\Database\MySQL\Helpers\QueryBuilder;
	use DigitalSplash\Database\MySQL\Models\Base\IndexedArray;
	use DigitalSplash\Database\MySQL\Models\Binds;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
	use DigitalSplash\Helpers\Helper;
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

			$indexedArray = new IndexedArray($implodeValue, $statementPrefix);
		}

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

        public function generateStringStatementProvider(): array {
            return [
                'empty array' => [
                    'array' => [],
                    'expectFinalString' => ''
                ],
                'array with one element' => [
                    'array' => [
                        'key1' => 'value1'
                    ],
                    'expectFinalString' => 'SET `key1` = :key1'
                ],
                'array with two elements' => [
                    'array' => [
                        'key1' => 'value1',
                        'key2' => 'value2'
                    ],
                    'expectFinalString' => 'SET `key1` = :key1, `key2` = :key2'
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
            $indexedArray = new IndexedArray(', ', 'SET');
            $indexedArray->setArray($array);
            $indexedArray->generateStringStatement();

            $this->assertEquals($expectFinalString, $indexedArray->getFinalString());
        }
	}