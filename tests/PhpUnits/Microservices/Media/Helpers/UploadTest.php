<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Helpers\Upload;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Media\Models\DocumentsExtensions;
	use DigitalSplash\Media\Models\File;

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

		//! IMPORTANT:: I NEED THIS FOR LATER
		// public function testIsFileFormatAllowed(): void {
		// 	$files = [
		// 		'files' => [
		// 			'name' => ['file1.txt', 'file2.png', 'file3.jpg'],
		// 			'type' => ['text/plain', ImagesExtensions::PNG, ImagesExtensions::JPG],
		// 			'size' => [1024, 2048, 3072],
		// 			'tmp_name' => ['/tmp/phpABC123', '/tmp/phpDEF456', '/tmp/phpGHI789'],
		// 			'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_OK, UPLOAD_ERR_OK]
		// 		]
		// 	];

		// 	$upload = new Upload($files);
		// 	$upload->upload();
		// 	// // How to pass the file type
		// 	// //?

		// 	$file1 = new File('file_1', 'file1.txt', 'text/plain', '/tmp/phpABC123',  UPLOAD_ERR_OK, 1024);
		// 	$this->assertEquals(false, $upload->isFileFormatAllowed($file1));
		// 	// $this->assertEquals(true, $upload->isFileFormatAllowed(1));
		// 	// $this->assertEquals(true, $upload->isFileFormatAllowed(2));
		// 	$this->assertTrue(true);
		// }
	}