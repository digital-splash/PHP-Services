<?php

	namespace DigitalSplash\Tests\Exceptions;

	use DigitalSplash\Exceptions\UnknownException;
	use PHPUnit\Framework\TestCase;

	final class UnknownExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new UnknownException();
			} catch (UnknownException $e) {
				$this->assertNotEquals('exception.main.unknown', $e->getMessage());
			}
		}
	}
