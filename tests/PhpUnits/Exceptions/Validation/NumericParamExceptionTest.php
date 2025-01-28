<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\NumericParamException;
	use PHPUnit\Framework\TestCase;

	final class NumericParamExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new NumericParamException('{{Params goes here...}}');
			} catch (NumericParamException $e) {
				$this->assertNotEquals('exception.validation.shouldBeNumericParam', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
