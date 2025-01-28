<?php

	namespace DigitalSplash\Tests\Exceptions\Auth;

	use DigitalSplash\Exceptions\Auth\TokenExpiredException;
	use PHPUnit\Framework\TestCase;

	class TokenExpiredExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new TokenExpiredException();
			} catch (TokenExpiredException $e) {
				$this->assertNotEquals('exception.api.tokenExpired', $e->getMessage());
			}
		}
	}