<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models\Base;

	use DigitalSplash\Database\MySQL\Models\Base\NonIndexedArray;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
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

    }