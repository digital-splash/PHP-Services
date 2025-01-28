<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidArgumentException;
	use PHPUnit\Framework\TestCase;

	final class InvalidArgumentExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidArgumentException('{{Argument}}', '{{Value}}');
			} catch (InvalidArgumentException $e) {
				$this->assertNotEquals('exception.validation.invalidArgument', $e->getMessage());
				$this->assertStringContainsString('{{Argument}}', $e->getMessage());
				$this->assertStringContainsString('{{Value}}', $e->getMessage());
			}
		}

		public function testExceptionWithAllowedMessage(): void {
			try {
				throw new InvalidArgumentException('{{Argument}}', '{{Value}}', '{{Allowed}}');
			} catch (InvalidArgumentException $e) {
				$this->assertNotEquals('exception.validation.invalidArgumentWithAllowed', $e->getMessage());
				$this->assertStringContainsString('{{Argument}}', $e->getMessage());
				$this->assertStringContainsString('{{Value}}', $e->getMessage());
				$this->assertStringContainsString('{{Allowed}}', $e->getMessage());
			}
		}
	}
