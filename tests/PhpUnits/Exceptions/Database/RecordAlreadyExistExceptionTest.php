<?php

	namespace DigitalSplash\Tests\Exceptions\Database;

	use DigitalSplash\Exceptions\Database\RecordAlreadyExistException;
	use PHPUnit\Framework\TestCase;

	final class RecordAlreadyExistExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new RecordAlreadyExistException();
			} catch (RecordAlreadyExistException $e) {
				$this->assertNotEquals('exception.database.recordAlreadyExist', $e->getMessage());
			}
		}
	}
