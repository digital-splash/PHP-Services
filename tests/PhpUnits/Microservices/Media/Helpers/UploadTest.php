<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Helpers\Upload;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Media\Models\DocumentsExtensions;

	class UploadTest extends TestCase {
		private const UPLOAD_DIR = __DIR__ . "/../../_CommonFiles/Upload/";

		public function testUploadToServer(): void {
			 // Create a temporary file to use as the uploaded file
			 $file = tmpfile();
			 fwrite($file, 'Test data');
			 $filePath = stream_get_meta_data($file)['uri'];
			 // Simulate the uploaded file using $_FILES superglobal
			 $_FILES['test_file'] = array(
				 'name' => 'test.txt',
				 'type' => 'text/plain',
				 'tmp_name' => $filePath,
				 'error' => 0,
				 'size' => filesize($filePath)
			 );
			 $upload = new Upload();
			 // Mock the file upload in your method
			 $result = $upload->uploadToServer();
			 // Assert the result
			 $this->assertEquals([
				"status"	=> 1,
				"message"	=> "File successfully uploaded!",
				"fileName"	=> 'test.txt'
			], $result);
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

		public function testBuildFiles(): void {
			// Mock file data
			$files = [
				'name' => ['file1.txt', 'file2.txt', 'file3.txt'],
				'type' => ['text/plain', 'text/plain', 'text/plain'],
				'size' => [1024, 2048, 3072],
				'tmp_name' => ['/tmp/phpABC123', '/tmp/phpDEF456', '/tmp/phpGHI789'],
				'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_OK, UPLOAD_ERR_OK]
			];

			// Expected result
			$expected = [
				['file1.txt', 'text/plain', 1024, '/tmp/phpABC123', UPLOAD_ERR_OK],
				['file2.txt', 'text/plain', 2048, '/tmp/phpDEF456', UPLOAD_ERR_OK],
				['file3.txt', 'text/plain', 3072, '/tmp/phpGHI789', UPLOAD_ERR_OK],
			];

			// Instantiate object
			$obj = new Upload();

			// Call the function
			$obj->buildFiles($files);

			// Check the result
			$this->assertEqualsCanonicalizing($expected, $obj->getFiles());
		}
	}