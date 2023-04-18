<?php
    namespace DigitalSplash\Tests\Database\MySQL\Models;

    use DigitalSplash\Database\MySQL\Models\Order;
    use PHPUnit\Framework\TestCase;

    class OrderTest extends TestCase{
            
        public function generateStringStatementProvider(): array {
			return [
                'empty array' => [
                    'array' => [],
                    'expectFinalString' => ''
                ],
                'one element' => [
                    'array' => ['name'],
                    'expectFinalString' => 'ORDER BY name'
                ],
                'two elements' => [
                    'array' => ['name', 'age'],
                    'expectFinalString' => 'ORDER BY name, age'
                ]
            ];
		}

        /**
		 * @dataProvider generateStringStatementProvider
		 */
        public function testGenerateStringStatement(array $array, string $expectFinalString): void {
            $group = new Order();
            $group->setArray($array);
            $group->generateStringStatement();
            $this->assertEquals($expectFinalString, $group->getFinalString());
        }
       
    }