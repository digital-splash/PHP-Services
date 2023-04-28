<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Helpers\Upload;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Media\Models\DocumentsExtensions;

	class UploadTest extends TestCase {
		private const UPLOAD_DIR = __DIR__ . "/../../_CommonFiles/Upload/";

		public function testUploadToServer(): void {
			$upload = new Upload();
			$tmpName =  __DIR__ . "../../../_CommonFiles/Upload/test.txt";
			$uploadPath = self::UPLOAD_DIR;

			$this->mockfi

			$fileName = "file.txt";
			$expectedResult = [
				"status" => 1,
				"message" => "File successfully uploaded!",
				"fileName" => $fileName
			];

			$result =$upload->uploadToServer($tmpName, $uploadPath, $fileName);
			$this->assertEqualsCanonicalizing($expectedResult, $result);

			unlink($uploadPath . $fileName);
		}

		public function testSafeName(): void {
			$upload = new Upload();
			$fileName = "testfile.txt";

			$result = $upload->safeName($fileName);

			$this->assertEquals("testfiletxt", $result);
		}

		public function testCheckExtensionValidity(): void {
			$upload = new Upload();
			$files = [
				"testfile.jpg",
				"testfile.png",
				"testfile.gif",
				"testfile.pdf",
				"testfile.doc",
				"testfile.docx",
				"testfile.xls",
				"testfile.xlsx",
				"testfile.ppt",
				"testfile.pptx",
			];

			$result = $upload->checkExtensionValidity($files);

			$this->assertEquals(true, $result);
		}
	}