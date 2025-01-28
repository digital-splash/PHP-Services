<?php

	namespace DigitalSplash\Tests\Exceptions\Media;

	use DigitalSplash\Exceptions\Media\UploadException;
	use PHPUnit\Framework\TestCase;

	final class UploadExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new UploadException();
			} catch (UploadException $e) {
				$this->assertNotEquals('exception.media.upload', $e->getMessage());
			}
		}
	}
