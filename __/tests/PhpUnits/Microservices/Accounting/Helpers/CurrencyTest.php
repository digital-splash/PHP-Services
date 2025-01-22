<?php
	namespace DigitalSplash\Tests\Accounting\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Exceptions\NotNumericParamException;
	use DigitalSplash\Accounting\Helpers\Currency;
	use DigitalSplash\Accounting\Models\CurrencyPosition;
	use DigitalSplash\Language\Helpers\Translate;

	final class CurrencyTest extends TestCase {

		public function AddCurrencySuccessProvider(): array {
			return [
				"UsdPreWithSpace" => [
					"$ 10",
					[10, "$", CurrencyPosition::PRE, " "]
				],
				"UsdPreWithoutSpace" => [
					"$10",
					[10, "$", CurrencyPosition::PRE, ""]
				],
				"UsdPostWithSpace" => [
					"10 $",
					[10, "$", CurrencyPosition::POST, " "]
				],
				"UsdPostWithoutSpace" => [
					"10$",
					[10, "$", CurrencyPosition::POST, ""]
				],
			];
		}

		/**
		 * @dataProvider AddCurrencySuccessProvider
		 */
		public function testAddCurrencySuccess(
			string $expected,
			array $arguments
		): void {
			$this->assertEquals(
				$expected,
				Currency::AddCurrency(
					$arguments[0],
					$arguments[1],
					$arguments[2],
					$arguments[3],
				)
			);
		}

		public function testGetLbpAmountNonNumericValueFail(): void {
			$this->expectException(NotNumericParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotNumericParam", null, [
				"::params::" => "amount"
			]));
			Currency::GetLbpAmount("John");
		}

		public function GetLbpAmountSuccessProvider(): array {
			return [
				[10000, 10000],
				[10250, 10050.04],
				[10500, 10250.01],
				[10750, 10650],
				[10750, 10750],
				[11000, 10751],
			];
		}

		/**
		 * @dataProvider GetLbpAmountSuccessProvider
		 */
		public function testGetLbpAmountSuccess(
			int $expected,
			float $argument
		): void {
			$this->assertEquals(
				$expected,
				Currency::GetLbpAmount($argument)
			);
		}

	}
