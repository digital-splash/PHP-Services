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
			$size = 1000;

			$file = new File($elemName, $name, $type, $tmpName, $error, $size);

			$this->assertEquals($elemName, $file->getElemName());
			$this->assertEquals($name, $file->getName());
			$this->assertEquals($type, $file->getType());
			$this->assertEquals($tmpName, $file->getTmpName());
			$this->assertEquals($error, $file->getError());
			$this->assertEquals($size, $file->getSize());
		}

		public function testValidateFile_FileNotUploadedThrows(): void {
			$elemName = "testElemName";
			$name = "testName.txt";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = 1000;

			$file = new File($elemName, $name, $type, $tmpName, $error, $size);

			$this->expectException(UploadException::class);
			$this->expectExceptionMessage('An unknown error occured while uploading the file');

			$file->validateFile();
		}

		public function testValidateFile_NoFormatPassedThrows(): void {
			$this->assertTrue(true);
		}

		public function testValidateFile_NotAllowedExtensionThrows(): void {
			$this->assertTrue(true);
		}

		// public function testIsFileUploaded(): void {


		// 	// $fileMock = new File($elemName, $name, $type, $tmpName, $error, $size);

			//Mock 1
			// $fileMock = $this->getMockBuilder(File::class)
			// 	->setConstructorArgs([$elemName, $name, $type, $tmpName, $error, $size])
			// 	->getMock();
			// $fileMock->expects($this->once())
			// 	->method('isFileUploaded')
			// 	->willReturn(true);

		// 	// Mock 2
		// 	$fileMock = $this->createMock(File::class);
		// 	$fileMock->expects($this->once())
		// 		->method('isFileUploaded')
		// 		->willReturn(true);

		// 	$this->assertTrue($fileMock->isFileUploaded());
		// }

		// public function isFileFormatAllowedThrowsProvider(): array {
		// 	//images
		// 	return [
		// 		["testName.pdf"],
		// 		["testName.doc"],
		// 		["testName.docx"],
		// 		["testName.xls"],
		// 		["testName.xlsx"],
		// 	];
		// }

		// /**
		//  * @dataProvider isFileFormatAllowedThrowsProvider
		//  */
		// public function testIsFileFormatThrowsNotAllowed(string $name): void {
		// 	$elemName = "testElemName";
		// 	$type = "testType";
		// 	$tmpName = "testTmpName";
		// 	$error = 0;
		// 	$size = "testSize";

		// 	$file = new File($elemName, $name, $type, $tmpName, $error, $size);
		// 	$upload = new Upload([]);

		// 	$this->expectException(UploadException::class);
		// 	$file->isFileFormatAllowed($upload->getAllowedExtensions());
		// }


		// public function isFileFormatAllowedSuccessProvider(): array {
		// 	//images
		// 	return [
		// 		["testName.jpg", true],
		// 		["testName.jpeg", true],
		// 		["testName.png", true],
		// 		["testName.gif", true],
		// 		["testName.webp", true],
		// 	];
		// }

		// /**
		//  * @dataProvider isFileFormatAllowedSuccessProvider
		//  */
		// public function testIsFileFormatSuccessAllowed(string $name, $expected): void {
		// 	$elemName = "testElemName";
		// 	$type = "testType";
		// 	$tmpName = "testTmpName";
		// 	$error = 0;
		// 	$size = "testSize";

		// 	$file = new File($elemName, $name, $type, $tmpName, $error, $size);
		// 	$upload = new Upload([]);

		// 	$this->assertEquals($expected, $file->isFileFormatAllowed($upload->getAllowedExtensions()));
		// }

		// public function handleUploadFileErrorProvider(): array {
		// 	return [
		// 		[1, "The uploaded file exceeds the upload_max_filesize directive in php.ini."],
		// 		[2, "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."],
		// 		[3, "The uploaded file was only partially uploaded."],
		// 		[4, "No file was uploaded."],
		// 		[6, "Missing a temporary folder."],
		// 		[7, "Failed to write file to disk."],
		// 		[8, "A PHP extension stopped the file upload."],
		// 	];
		// }

		// /**
		//  * @dataProvider handleUploadFileErrorProvider
		//  */
		// public function testHandleUploadFileError(int $error, string $expected): void {
		// 	$elemName = "testElemName";
		// 	$name = "testName";
		// 	$type = "testType";
		// 	$tmpName = "testTmpName";
		// 	$size = "testSize";

		// 	$file = new File($elemName, $name, $type, $tmpName, $error, $size);

		// 	$this->expectException(UploadException::class);
		// 	$this->expectExceptionMessage($expected);
		// 	$file->handleUploadFileError();
		// }

		public function testValidateFileSuccess(): void {
			$elemName = "testElemName";
			$name = "testName.txt";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = 1000;

			//Mock 1
			$fileMock = $this->getMockBuilder(File::class)
				->setConstructorArgs([$elemName, $name, $type, $tmpName, $error, $size])
				->onlyMethods([
					'isFileUploaded',
				])
				->getMock();
			$fileMock->expects($this->once())
				->method('isFileUploaded');

			$fileMock->validateFile(['txt']);

			// //Mock 2
			// $fileMock = $this->createMock(File::class);
			// $fileMock->expects($this->once())
			// 	->method('isFileUploaded');
			// $fileMock->validateFile();
		}

	}
