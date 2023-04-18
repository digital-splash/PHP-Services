<?php
    namespace DigitalSplash\Tests\Database\MySQL\Models;

    use DigitalSplash\Database\MySQL\Models\Group;
    use PHPUnit\Framework\TestCase;

    class GroupTest extends TestCase{
            
        public function generateStringStatementProvider(): array {
			return [
                'empty array' => [
                    'array' => [],
                    'expectFinalString' => ''
                ],
                'one element' => [
                    'array' => ['name'],
                    'expectFinalString' => 'GROUP BY name'
                ],
                'two elements' => [
                    'array' => ['name', 'age'],
                    'expectFinalString' => 'GROUP BY name, age'
                ]
            ];
		}

        /**
		 * @dataProvider generateStringStatementProvider
		 */
        public function testGenerateStringStatement(array $array, string $expectFinalString): void {
            $group = new Group();
            $group->setArray($array);
            $group->generateStringStatement();
            $this->assertEquals($expectFinalString, $group->getFinalString());
        }
       
    }