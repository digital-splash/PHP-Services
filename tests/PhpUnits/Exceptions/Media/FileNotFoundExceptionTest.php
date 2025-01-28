<?php

	namespace DigitalSplash\Tests\Exceptions\Media;

	use DigitalSplash\Exceptions\Media\FileNotFoundException;
	use PHPUnit\Framework\TestCase;

	final class FileNotFoundExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new FileNotFoundException('{{Params goes here...}}');
			} catch (FileNotFoundException $e) {
				$this->assertNotEquals('exception.media.fileNotFound', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
