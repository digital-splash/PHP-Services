<?php
    namespace DigitalSplash\Tests\Database\MySQL\Models;

    use DigitalSplash\Database\MySQL\Models\Having;
use PDO;
use PHPUnit\Framework\TestCase;

    class HavingTest extends TestCase {
                
        public function generateStringStatementProvider(): array {
			return [
                'empty array' => [
                    'array' => [],
                    'expectFinalString' => '',
                    'expectBinds' => []
                ],
                'one element' => [
                    'array' => [
                        'name' => 'John'
                    ],
                    'expectFinalString' => 'HAVING `name` = :name',
                    'expectBinds' => [
                        ':name' => [
                            'value' => 'John',
                            'type' => PDO::PARAM_STR
                        ]
                    ]
                ],
                'two elements' => [
                    'array' => [
                        'name' => 'John',
                        'age' => 25
                    ],
                    'expectFinalString' => 'HAVING `name` = :name AND `age` = :age',
                    'expectBinds' => [
                        ':name' => [
                            'value' => 'John',
                            'type' => PDO::PARAM_STR
                        ],
                        ':age' => [
                            'value' => 25,
                            'type' => PDO::PARAM_INT
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
            $having = new Having();
            $having->setArray($array);
            $having->generateStringStatement();
            $this->assertEquals($expectFinalString, $having->getFinalString());
            $this->assertEquals($expectBinds, $having->binds->getBinds());
        }
    }