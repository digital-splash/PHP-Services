<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidPhoneNumberException;
	use PHPUnit\Framework\TestCase;

	final class InvalidPhoneNumberExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidPhoneNumberException();
			} catch (InvalidPhoneNumberException $e) {
				$this->assertNotEquals('exception.validation.invalidPhoneNumber', $e->getMessage());
			}
		}
	}
