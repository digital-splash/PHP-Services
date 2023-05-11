<?php
	namespace DigitalSplash\Tests\Media\Models;

	use DigitalSplash\Exceptions\UploadException;
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

		public function toArray(): void {
			$elemName = "testElemName";
			$name = "testName.txt";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = 1000;

			$file = new File($elemName, $name, $type, $tmpName, $error, $size);
			$actual = $file->toArray();
			$expected = [
				'name' => $name,
				'type' => $type,
				'tmp_name' => $tmpName,
				'error' => $error,
				'size' => $size,
			];

			$this->assertEqualsCanonicalizing($expected, $actual);
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
			$elemName = "testElemName";
			$name = "testName.txt";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = 1000;

			$fileMock = $this->getMockBuilder(File::class)
				->setConstructorArgs([$elemName, $name, $type, $tmpName, $error, $size])
				->onlyMethods([
					'isFileUploaded',
				])
				->getMock();
			$fileMock->expects($this->once())
				->method('isFileUploaded');

			$this->expectException(UploadException::class);
			$this->expectExceptionMessage('You should define at least one supported extension');

			$fileMock->validateFile([]);

		}

		public function testValidateFile_NotAllowedExtensionThrows(): void {
			$elemName = "testElemName";
			$name = "testName.txt";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = 1000;

			$fileMock = $this->getMockBuilder(File::class)
				->setConstructorArgs([$elemName, $name, $type, $tmpName, $error, $size])
				->onlyMethods([
					'isFileUploaded',
				])
				->getMock();
			$fileMock->expects($this->once())
				->method('isFileUploaded');

			$this->expectException(UploadException::class);
			$this->expectExceptionMessage('File extension is not allowed! Allowed extensions: jpg, png');

			$fileMock->validateFile(['jpg', 'png']);
		}

		/**
		 * @dataProvider handleUploadFileErrorProvider
		 */
		public function testHandleUploadFileError(int $error, string $expected): void {
			$elemName = "testElemName";
			$name = "testName.txt";
			$type = "testType";
			$tmpName = "testTmpName";
			$size = 1000;

			$fileMock = $this->getMockBuilder(File::class)
				->setConstructorArgs([$elemName, $name, $type, $tmpName, $error, $size])
				->onlyMethods([
					'isFileUploaded',
				])
				->getMock();
			$fileMock->expects($this->once())
				->method('isFileUploaded');

			$this->expectException(UploadException::class);
			$this->expectExceptionMessage($expected);

			$fileMock->validateFile(['txt']);
		}

		public function handleUploadFileErrorProvider(): array {
			return [
				[UPLOAD_ERR_INI_SIZE, 'The uploaded file exceeds the upload_max_filesize directive in php.ini'],
				[UPLOAD_ERR_FORM_SIZE, 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'],
				[UPLOAD_ERR_PARTIAL, 'The uploaded file was only partially uploaded'],
				[UPLOAD_ERR_NO_FILE, 'No file was uploaded'],
				[UPLOAD_ERR_NO_TMP_DIR, 'Missing a temporary folder'],
				[UPLOAD_ERR_CANT_WRITE, 'Failed to write file to disk'],
				[UPLOAD_ERR_EXTENSION, 'A PHP extension stopped the file upload'],
				[10, 'Unknown upload error'],
			];
		}

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
