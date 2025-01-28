<?php

	namespace DigitalSplash\Tests\Exceptions\Configuration;

	use DigitalSplash\Exceptions\Configuration\ConfigurationNotFoundException;
	use PHPUnit\Framework\TestCase;

	final class ConfigurationNotFoundExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new ConfigurationNotFoundException();
			} catch (ConfigurationNotFoundException $e) {
				$this->assertNotEquals('exception.configuration.notFound', $e->getMessage());
			}
		}
	}
