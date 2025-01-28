<?php

	namespace DigitalSplash\Tests\Exceptions\Auth;

	use DigitalSplash\Exceptions\Auth\InvalidTokenException;
	use PHPUnit\Framework\TestCase;

	class InvalidTokenExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidTokenException();
			} catch (InvalidTokenException $e) {
				$this->assertNotEquals('exception.api.invalidToken', $e->getMessage());
			}
		}
	}