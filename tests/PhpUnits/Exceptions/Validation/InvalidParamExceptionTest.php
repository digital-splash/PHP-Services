<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidParamException;
	use PHPUnit\Framework\TestCase;

	final class InvalidParamExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidParamException('{{Params goes here...}}');
			} catch (InvalidParamException $e) {
				$this->assertNotEquals('exception.validation.invalidParam', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
