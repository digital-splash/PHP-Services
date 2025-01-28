<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\InvalidNumberException;
	use PHPUnit\Framework\TestCase;

	final class InvalidNumberExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidNumberException();
			} catch (InvalidNumberException $e) {
				$this->assertNotEquals('exception.validation.invalidNumber', $e->getMessage());
			}
		}
	}
