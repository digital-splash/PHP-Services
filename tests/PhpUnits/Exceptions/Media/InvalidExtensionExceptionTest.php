<?php

	namespace DigitalSplash\Tests\Exceptions\Media;

	use DigitalSplash\Exceptions\Media\InvalidExtensionException;
	use PHPUnit\Framework\TestCase;

	final class InvalidExtensionExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new InvalidExtensionException('{{Params goes here...}}');
			} catch (InvalidExtensionException $e) {
				$this->assertNotEquals('exception.media.invalidExtension', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
