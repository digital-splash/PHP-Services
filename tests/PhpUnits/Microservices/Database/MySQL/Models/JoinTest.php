<?php
    namespace DigitalSplash\Tests\Database\MySQL\Models;

    use DigitalSplash\Database\MySQL\Models\Join;
    use PHPUnit\Framework\TestCase;

    class JoinTest extends TestCase{
            
        public function generateStringStatementProvider(): array {
			return [
                'empty array' => [
                    'array' => [],
                    'expectFinalString' => ''
                ],
                'one element' => [
                    'array' => ['JOIN `table` ON `table`.`id` = `table2`.`id`'],
                    'expectFinalString' => 'JOIN `table` ON `table`.`id` = `table2`.`id`'
                ],
                'two elements' => [
                    'array' => ['JOIN `table` ON `table`.`id` = `table2`.`id`', 'JOIN `table3` ON `table3`.`id` = `table4`.`id`'],
                    'expectFinalString' => 'JOIN `table` ON `table`.`id` = `table2`.`id` JOIN `table3` ON `table3`.`id` = `table4`.`id`'
                ]
            ];
		}

        /**
		 * @dataProvider generateStringStatementProvider
		 */
        public function testGenerateStringStatement(array $array, string $expectFinalString): void {
            $group = new Join();
            $group->setArray($array);
            $group->generateStringStatement();
            $this->assertEquals($expectFinalString, $group->getFinalString());
        }
       
    }