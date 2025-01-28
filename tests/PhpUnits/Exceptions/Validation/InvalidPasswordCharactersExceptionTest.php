<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidPasswordCharactersException;
	use PHPUnit\Framework\TestCase;

	final class InvalidPasswordCharactersExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidPasswordCharactersException();
			} catch (InvalidPasswordCharactersException $e) {
				$this->assertNotEquals('exception.validation.invalidPasswordCharacters', $e->getMessage());
			}
		}
	}
