<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidTypeException;
	use PHPUnit\Framework\TestCase;

	final class InvalidTypeExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidTypeException('{{PropertyName}}', '{{ExpectedType}}', '{{Value}}');
			} catch (InvalidTypeException $e) {
				$this->assertNotEquals('exception.validation.invalidType', $e->getMessage());
				$this->assertStringContainsString('{{PropertyName}}', $e->getMessage());
				$this->assertStringContainsString('{{ExpectedType}}', $e->getMessage());
				$this->assertStringContainsString('string', $e->getMessage());
			}
		}
	}
