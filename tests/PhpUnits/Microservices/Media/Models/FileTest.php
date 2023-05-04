<?php
	namespace DigitalSplash\Tests\Media\Models;

use DigitalSplash\Exceptions\UploadException;
use DigitalSplash\Media\Helpers\Upload;
	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Models\File;

	class FileTest extends TestCase {
		public function testGetters(): void {
			$elemName = "testElemName";
			$name = "testName";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = "testSize";

			$file = new File($elemName, $name, $type, $tmpName, $error, $size);

			$this->assertEquals($elemName, $file->getElemName());
			$this->assertEquals($name, $file->getName());
			$this->assertEquals($type, $file->getType());
			$this->assertEquals($tmpName, $file->getTmpName());
			$this->assertEquals($error, $file->getError());
			$this->assertEquals($size, $file->getSize());
		}

		public function testIsFileUploaded(): void {
			$elemName = "testElemName";
			$name = "testName.txt";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = "testSize";

			$file = new File($elemName, $name, $type, $tmpName, $error, $size);

			$upload = new Upload($file->createFile());
			$upload->upload();

			$this->assertTrue($file->isFileUploaded());
		}

		public function isFileFormatAllowedThrowsProvider(): array {
			//images
			return [
				["testName.pdf"],
				["testName.doc"],
				["testName.docx"],
				["testName.xls"],
				["testName.xlsx"],
			];
		}

		/**
		 * @dataProvider isFileFormatAllowedThrowsProvider
		 */
		public function testIsFileFormatThrowsNotAllowed(string $name): void {
			$elemName = "testElemName";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = "testSize";

			$file = new File($elemName, $name, $type, $tmpName, $error, $size);
			$upload = new Upload([]);

			$this->expectException(UploadException::class);
			$file->isFileFormatAllowed($upload->getAllowedExtensions());
		}


		public function isFileFormatAllowedSuccessProvider(): array {
			//images
			return [
				["testName.jpg", true],
				["testName.jpeg", true],
				["testName.png", true],
				["testName.gif", true],
				["testName.webp", true],
			];
		}

		/**
		 * @dataProvider isFileFormatAllowedSuccessProvider
		 */
		public function testIsFileFormatSuccessAllowed(string $name, $expected): void {
			$elemName = "testElemName";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = "testSize";

			$file = new File($elemName, $name, $type, $tmpName, $error, $size);
			$upload = new Upload([]);

			$this->assertEquals($expected, $file->isFileFormatAllowed($upload->getAllowedExtensions()));
		}

	}