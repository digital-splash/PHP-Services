<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidUsernameLengthException;
	use PHPUnit\Framework\TestCase;

	final class InvalidUsernameLengthExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidUsernameLengthException();
			} catch (InvalidUsernameLengthException $e) {
				$this->assertNotEquals('exception.validation.invalidUsernameLength', $e->getMessage());
			}
		}
	}
