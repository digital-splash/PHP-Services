<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Limit;
	use PHPUnit\Framework\TestCase;

	class LimitTest extends TestCase{

		public function generateStringStatementProvider(): array {
			return [
				'empty value' => [
					'value' => '',
					'expectFinalString' => ''
				],
				'one element' => [
					'value' => 1,
					'expectFinalString' => 'LIMIT 1'
				],
			];
		}

		/**
		 * @dataProvider generateStringStatementProvider
		 */
		public function testGenerateStringStatement($value, string $expectFinalString): void {
			$limit = new Limit();
			$limit->setValue($value);
			$limit->generateStringStatement();
			$this->assertEquals($expectFinalString, $limit->getFinalString());
		}
	}
