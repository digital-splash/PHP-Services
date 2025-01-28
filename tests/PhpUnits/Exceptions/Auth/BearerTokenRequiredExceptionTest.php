<?php

	namespace DigitalSplash\Tests\Exceptions\Auth;

	use DigitalSplash\Exceptions\Auth\BearerTokenRequiredException;
	use PHPUnit\Framework\TestCase;

	class BearerTokenRequiredExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new BearerTokenRequiredException();
			} catch (BearerTokenRequiredException $e) {
				$this->assertNotEquals('exception.api.bearerRequired', $e->getMessage());
			}
		}
	}