<?php

	namespace DigitalSplash\Tests\Exceptions\Configuration;

	use DigitalSplash\Exceptions\Configuration\InvalidConfigurationException;
	use PHPUnit\Framework\TestCase;

	final class InvalidConfigurationExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidConfigurationException();
			} catch (InvalidConfigurationException $e) {
				$this->assertNotEquals('exception.configuration.invalid', $e->getMessage());
			}
		}
	}
