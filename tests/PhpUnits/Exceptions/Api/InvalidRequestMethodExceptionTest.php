<?php

	namespace DigitalSplash\Tests\Exceptions\Api;

	use DigitalSplash\Exceptions\Api\InvalidRequestMethodException;
	use PHPUnit\Framework\TestCase;

	final class InvalidRequestMethodExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidRequestMethodException();
			} catch (InvalidRequestMethodException $e) {
				$this->assertNotEquals('exception.api.invalidRequestMethod', $e->getMessage());
			}
		}
	}
