<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\AlreadyExistException;
	use PHPUnit\Framework\TestCase;

	final class AlreadyExistExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new AlreadyExistException('{{Params goes here...}}');
			} catch (AlreadyExistException $e) {
				$this->assertNotEquals('exception.validation.parameterAlreadyExists', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
