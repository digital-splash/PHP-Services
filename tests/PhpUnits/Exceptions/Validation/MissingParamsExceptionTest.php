<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\MissingParamsException;
	use PHPUnit\Framework\TestCase;

	final class MissingParamsExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new MissingParamsException([
					'param1',
					'param2',
					'param3',
				]);
			} catch (MissingParamsException $e) {
				$this->assertNotEquals('exception.validation.missingParameters', $e->getMessage());
				$this->assertStringContainsString('`param1`, `param2`, `param3`', $e->getMessage());
			}
		}
	}
