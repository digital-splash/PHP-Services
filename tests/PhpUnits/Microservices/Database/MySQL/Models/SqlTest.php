<?php
    namespace DigitalSplash\Tests\Database\MySQL\Models;

    use DigitalSplash\Database\MySQL\Models\Sql;
    use PHPUnit\Framework\TestCase;

    class SqlTest extends TestCase{
            
        public function generateStringStatementProvider(): array {
            return [
                'empty value' => [
                    'value' => '',
                    'expectFinalString' => ''
                ],
                'one element' => [
                    'value' => 'SELECT * FROM table',
                    'expectFinalString' => 'SELECT * FROM table'
                ],    
            ];
        }

        /**
         * @dataProvider generateStringStatementProvider
         */
        public function testGenerateStringStatement($value, string $expectFinalString): void {
            $limit = new Sql();
            $limit->setValue($value);
            $limit->generateStringStatement();
            $this->assertEquals($expectFinalString, $limit->getFinalString());
        }
    }
    