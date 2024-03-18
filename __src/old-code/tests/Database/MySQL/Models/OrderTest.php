<?php
	namespace OldCode\DigitalSplash\Tests\Database\MySQL\Models;

	use OldCode\DigitalSplash\Database\MySQL\Models\Order;
	use PHPUnit\Framework\TestCase;

	class OrderTest extends TestCase{

		public function generateStringStatementProvider(): array {
			return [
				'empty array' => [
					'array' => [],
					'expectFinalString' => ''
				],
				'one element' => [
					'array' => [
						'name ASC'
					],
					'expectFinalString' => 'ORDER BY name ASC'
				],
				'multiple elements' => [
					'array' => [
						'name ASC',
						'age DESC',
						'email DESC',
					],
					'expectFinalString' => 'ORDER BY name ASC, age DESC, email DESC'
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
