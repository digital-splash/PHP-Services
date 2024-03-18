<?php
	namespace OldCode\DigitalSplash\Tests\Database\MySQL\Models;

	use OldCode\DigitalSplash\Database\MySQL\Models\Group;
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
				'multiple elements' => [
					'array' => ['name', 'age', 'email'],
					'expectFinalString' => 'GROUP BY name, age, email'
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
