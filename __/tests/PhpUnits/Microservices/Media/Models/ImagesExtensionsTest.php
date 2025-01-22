<?php
	namespace DigitalSplash\Tests\Media\Models;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Models\ImagesExtensions;

	class ImagesExtensionsTest extends TestCase {

		public function testGetExtensions(): void {
			$this->assertEquals(
				[
					ImagesExtensions::JPG,
					ImagesExtensions::JPEG,
					ImagesExtensions::PNG,
					ImagesExtensions::GIF,
					ImagesExtensions::WEBP,
				],
				ImagesExtensions::getExtensions()
			);
		}
	}