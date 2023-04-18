<?php
    namespace DigitalSplash\Tests\Database\MySQL\Models;

    use DigitalSplash\Database\MySQL\Models\Where;
    use PDO;
    use PHPUnit\Framework\TestCase;

    class WhereTest extends TestCase {
                
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
                    'expectFinalString' => 'WHERE `name` = :name',
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
                    'expectFinalString' => 'WHERE `name` = :name AND `age` = :age',
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
            $having = new Where();
            $having->setArray($array);
            $having->generateStringStatement();
            $this->assertEquals($expectFinalString, $having->getFinalString());
            $this->assertEquals($expectBinds, $having->binds->getBinds());
        }
    }