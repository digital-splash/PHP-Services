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

        // ! IMPORTANT ask about the success test?? 

        public function testGetFinalString(): void {
            $nonIndexedArray = new NonIndexedArray(', ', 'SET');
            $this->assertEquals('', $nonIndexedArray->getFinalString());
        }

        public function testSetFinalString(): void {
            $nonIndexedArray = new NonIndexedArray(', ', 'SET');
            $nonIndexedArray->setFinalString('test');
            $this->assertEquals('test', $nonIndexedArray->getFinalString());
        }

        public function testClearFinalString(): void {
            $nonIndexedArray = new NonIndexedArray(', ', 'SET');
            $nonIndexedArray->setFinalString('test');
            $nonIndexedArray->clearFinalString();
            $this->assertEquals('', $nonIndexedArray->getFinalString());
        }
        //! ask if i should keep it canonicalizing
        public function testGetArray(): void {
            $nonIndexedArray = new NonIndexedArray(', ', 'SET');
            $this->assertEqualsCanonicalizing([], $nonIndexedArray->getArray());
        }

        public function testSetArray(): void {
            $nonIndexedArray = new NonIndexedArray(', ', 'SET');
            $nonIndexedArray->setArray(['test']);
            $this->assertEqualsCanonicalizing(['test'], $nonIndexedArray->getArray());
        }

        public function testClearArray(): void {
            $nonIndexedArray = new NonIndexedArray(', ', 'SET');
            $nonIndexedArray->setArray(['test']);
            $nonIndexedArray->clearArray();

            $this->assertEqualsCanonicalizing([], $nonIndexedArray->getArray());
        }

    }