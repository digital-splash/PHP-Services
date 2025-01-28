<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\EmptyValueException;
	use PHPUnit\Framework\TestCase;

	final class EmptyValueExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new EmptyValueException('{{Params goes here...}}');
			} catch (EmptyValueException $e) {
				$this->assertNotEquals('exception.validation.emptyValue', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
