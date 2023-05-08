<?php
	namespace DigitalSplash\Tests\Media\Models;

	use DigitalSplash\Media\Models\File;
	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Models\Files;
	use DigitalSplash\Media\Models\ImagesExtensions;

	class FilesTest extends TestCase {

		public function buildFilesProvider(): array {
			return [
				'single_file_one_level' => [
					'files' => [
						'file' => [
							'name' => 'file1.txt',
							'type' => 'text/plain',
    						'tmp_name' => '/tmp/phpD567.tmp',
							'error' => UPLOAD_ERR_OK,
							'size' => 1024
						]
					],
					'expected' => [
						// new File('file', 'file1.txt', 'text/plain', '/tmp/phpD567.tmp', UPLOAD_ERR_OK, 1024)
						'file' => [
							'name' => 'file1.txt',
							'type' => 'text/plain',
    						'tmp_name' => '/tmp/phpD567.tmp',
							'error' => UPLOAD_ERR_OK,
							'size' => 1024
						]
					]
				],
				'multiple_files_one_level' => [
					'files' => [
						'files' => [
							'name' => ['file1.txt', 'file2.png', 'file3.jpg'],
							'type' => ['text/plain', ImagesExtensions::PNG, ImagesExtensions::JPG],
							'size' => [1024, 2048, 3072],
							'tmp_name' => ['/tmp/phpABC123', '/tmp/phpDEF456', '/tmp/phpGHI789'],
							'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_OK, UPLOAD_ERR_OK]
						]
					],
					'expected' => [
						'[files][0]' => [
							'name' => 'file1.txt',
							'type' => 'text/plain',
							'size' => 1024,
							'tmp_name' => '/tmp/phpABC123',
							'error' => UPLOAD_ERR_OK
						],
						'[files][1]' => [
							'name' => 'file2.png',
							'type' => ImagesExtensions::PNG,
							'size' => 2048,
							'tmp_name' => '/tmp/phpDEF456',
							'error' => UPLOAD_ERR_OK
						],
						'[files][2]' => [
							'name' => 'file3.jpg',
							'type' => ImagesExtensions::JPG,
							'size' => 3072,
							'tmp_name' => '/tmp/phpGHI789',
							'error' => UPLOAD_ERR_OK
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

			$this->assertEqualsCanonicalizing($expected, $files_class->toArray());
		}
	}
