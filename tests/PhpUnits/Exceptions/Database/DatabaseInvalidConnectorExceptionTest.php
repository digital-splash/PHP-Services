<?php

	namespace DigitalSplash\Tests\Exceptions\Database;

	use DigitalSplash\Exceptions\Database\DatabaseInvalidConnectorException;
	use PHPUnit\Framework\TestCase;

	final class DatabaseInvalidConnectorExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new DatabaseInvalidConnectorException('{{Params goes here...}}');
			} catch (DatabaseInvalidConnectorException $e) {
				$this->assertNotEquals('exception.database.invalidConnector', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
