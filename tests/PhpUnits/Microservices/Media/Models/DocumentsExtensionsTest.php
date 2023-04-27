<?php
	namespace DigitalSplash\Tests\Media\Models;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Models\DocumentsExtensions;

	class DocumentsExtensionsTest extends TestCase {

		public function testGetExtensions(): void {
			$this->assertEquals(
				[
					DocumentsExtensions::PDF,
					DocumentsExtensions::DOC,
					DocumentsExtensions::DOCX,
					DocumentsExtensions::XLS,
					DocumentsExtensions::XLSX,
				],
				DocumentsExtensions::getExtensions()
			);
		}
	}