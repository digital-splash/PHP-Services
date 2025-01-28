<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidPasswordLengthException;
	use PHPUnit\Framework\TestCase;

	final class InvalidPasswordLengthExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidPasswordLengthException();
			} catch (InvalidPasswordLengthException $e) {
				$this->assertNotEquals('exception.validation.invalidPasswordLength', $e->getMessage());
			}
		}
	}
