<?php
	namespace DigitalSplash\Tests\Media\Models;

	use DigitalSplash\Exceptions\UploadException;
	use DigitalSplash\Media\Helpers\Upload;
	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Models\Files;
	use DigitalSplash\Media\Models\ImagesExtensions;

	class FilesTest extends TestCase {

		public function buildFilesProvider(): array {
			return [
				[
					"files"=>[
						'files' => [
							'name' => ['file1.txt', 'file2.png', 'file3.jpg'],
							'type' => ['text/plain', ImagesExtensions::PNG, ImagesExtensions::JPG],
							'size' => [1024, 2048, 3072],
							'tmp_name' => ['/tmp/phpABC123', '/tmp/phpDEF456', '/tmp/phpGHI789'],
							'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_OK, UPLOAD_ERR_OK]
						]
					],
					"expected"=>[
						[
							'file_1' => [
							'name' => 'file1.txt',
							'type' => 'text/plain',
							'size' => 1024,
							'tmp_name' => '/tmp/phpABC123',
							'error' => UPLOAD_ERR_OK
							]
						],
						[
							'file_2' => [
							'name' => 'file2.png',
							'type' => ImagesExtensions::PNG,
							'size' => 2048,
							'tmp_name' => '/tmp/phpDEF456',
							'error' => UPLOAD_ERR_OK
							]
						],
						[
							'file_3' => [
							'name' => 'file3.jpg',
							'type' => ImagesExtensions::JPG,
							'size' => 3072,
							'tmp_name' => '/tmp/phpGHI789',
							'error' => UPLOAD_ERR_OK
							]
						]
					]
				]

			];
		}

		/**
		 * @dataProvider buildFilesProvider
		 */
		public function testBuildFiles(array $files, array $expected): void {
			$files_class = new Files($files);
			$files_class->buildFiles();
			print_r($files_class->getFiles());

			$this->assertEquals($expected, $files_class->getFiles());
		}
	}
