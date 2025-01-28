<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidCookieException;
	use PHPUnit\Framework\TestCase;

	final class InvalidCookieExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidCookieException('{{Params goes here...}}');
			} catch (InvalidCookieException $e) {
				$this->assertNotEquals('exception.validation.invalidCookie', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
