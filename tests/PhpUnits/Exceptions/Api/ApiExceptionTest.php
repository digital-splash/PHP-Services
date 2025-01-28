<?php

	namespace DigitalSplash\Tests\Exceptions\Api;

	use DigitalSplash\Exceptions\Api\ApiException;
	use PHPUnit\Framework\TestCase;

	final class ApiExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new ApiException();
			} catch (ApiException $e) {
				$this->assertNotEquals('exception.api.unknown', $e->getMessage());
			}
		}
	}
