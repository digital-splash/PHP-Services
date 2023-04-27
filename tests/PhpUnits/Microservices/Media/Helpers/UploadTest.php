<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Helpers\Upload;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Media\Models\DocumentsExtensions;

	class UploadTest extends TestCase {

		public function testConstructor(): void {
			$upload = new Upload();
			$this->assertEquals("", $upload->_name);
			$this->assertEquals("", $upload->_type);
			$this->assertEquals("", $upload->_tmp_name);
			$this->assertEquals(0, $upload->_error);
			$this->assertEquals(0, $upload->_size);
			$this->assertEquals("", $upload->fileFullPath);
			$this->assertEquals("", $upload->elemName);
			$this->assertEquals("", $upload->uploadPath);
			$this->assertEquals("", $upload->folders);
			$this->assertEquals("", $upload->destName);
			$this->assertEquals(ImagesExtensions::getExtensions(), $upload->allowedExtensions);
			$this->assertEquals(0, $upload->ratio);
			$this->assertEquals(Upload::convertToNextGen, $upload->convertToNextGen);
			$this->assertEquals(true, $upload->resize);
			$this->assertEquals([], $upload->retArr);
			$this->assertEquals([], $upload->uploadedPaths);
			$this->assertEquals([], $upload->uploadedData);
			$this->assertEquals([], $upload->successArr);
			$this->assertEquals([], $upload->errorArr);
			$this->assertEquals(0, $upload->error);
			$this->assertEquals(false, $upload->isTest);

		}

		public function testUploadToServer(): void {
			$upload = new Upload();
			$tmpName = "tests\PhpUnits\Microservices\Media\Uploads\testfile";
			$uploadPath = "src\Microservices\Media\Uploads";
			$fileName = "testfile.txt";

			$result = $upload->uploadToServer($tmpName, $uploadPath, $fileName);

			$this->assertTrue(file_exists($uploadPath . $fileName));

			$this->assertEquals([
				"status"    => 1,
				"message"   => "File successfully uploaded!",
				"fileName"  => $fileName
			], $result);

			unlink($uploadPath . $fileName);
		}
	}