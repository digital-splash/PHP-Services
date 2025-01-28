<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidUrlException;
	use PHPUnit\Framework\TestCase;

	final class InvalidUrlExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidUrlException();
			} catch (InvalidUrlException $e) {
				$this->assertNotEquals('exception.validation.invalidUrl', $e->getMessage());
			}
		}
	}
