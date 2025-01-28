<?php

	namespace DigitalSplash\Tests\Exceptions;

	use DigitalSplash\Exceptions\NotFoundException;
	use PHPUnit\Framework\TestCase;

	final class NotFoundExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new NotFoundException();
			} catch (NotFoundException $e) {
				$this->assertNotEquals('exception.main.notFound', $e->getMessage());
			}
		}
	}
