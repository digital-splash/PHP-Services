<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidEmailException;
	use PHPUnit\Framework\TestCase;

	final class InvalidEmailExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidEmailException();
			} catch (InvalidEmailException $e) {
				$this->assertNotEquals('exception.validation.invalidEmail', $e->getMessage());
			}
		}
	}
