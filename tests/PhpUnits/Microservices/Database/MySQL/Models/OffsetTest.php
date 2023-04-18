<?php
    namespace DigitalSplash\Tests\Database\MySQL\Models;

    use DigitalSplash\Database\MySQL\Models\Offset;
    use PHPUnit\Framework\TestCase;

    class OffsetTest extends TestCase{
            
        public function generateStringStatementProvider(): array {
            return [
                'empty value' => [
                    'value' => '',
                    'expectFinalString' => ''
                ],
                'one element' => [
                    'value' => 1,
                    'expectFinalString' => 'OFFSET 1'
                ],   
            ];
        }

        /**
         * @dataProvider generateStringStatementProvider
         */
        public function testGenerateStringStatement($value, string $expectFinalString): void {
            $limit = new Offset();
            $limit->setValue($value);
            $limit->generateStringStatement();
            $this->assertEquals($expectFinalString, $limit->getFinalString());
        }
    }
    