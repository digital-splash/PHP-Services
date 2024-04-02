<?php
	namespace OldCode\DigitalSplash\Tests\Database\MySQL\Models\Base;

	use OldCode\DigitalSplash\Database\MySQL\Models\Base\SingleValue;
	use PHPUnit\Framework\TestCase;

	class SingleValueTest extends TestCase {

		public function testFinalStringAllCases(): void {
			$singleValue = new SingleValue('SET');
			$this->assertEmpty($singleValue->getFinalString());

			$value = 'This is a final String';
			$singleValue->setFinalString($value);
			$this->assertEquals($value, $singleValue->getFinalString());

			$singleValue->clearFinalString();
			$this->assertEmpty($singleValue->getFinalString());
		}

		public function testValueAllCases(): void {
			$singleValue = new SingleValue('SET');
			$this->assertNull($singleValue->getValue());

			$value = 'value';
			$singleValue->setValue($value);
			$this->assertEquals($value, $singleValue->getValue());

			$singleValue->clearValue();
			$this->assertNull($singleValue->getValue());
		}

		public function generateStringStatementProvider(): array {
			return [
				'empty' => [
					'value' => null,
					'expected' => ''
				],
				'not_empty' => [
					'value' => 'value',
					'expected' => 'SET value'
				]
			];
		}

		/**
		 * @dataProvider generateStringStatementProvider
		 */
		public function testGenerateStringStatement(
			$value,
			string $expected
		): void {
			$singleValue = new SingleValue('SET');
			$singleValue->setValue($value);
			$singleValue->generateStringStatement();

			$this->assertEquals($expected, $singleValue->getFinalString());
		}

	}
