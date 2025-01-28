<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidUsernameCharactersException;
	use PHPUnit\Framework\TestCase;

	final class InvalidUsernameCharactersExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidUsernameCharactersException();
			} catch (InvalidUsernameCharactersException $e) {
				$this->assertNotEquals('exception.validation.invalidUsernameCharacters', $e->getMessage());
			}
		}
	}
