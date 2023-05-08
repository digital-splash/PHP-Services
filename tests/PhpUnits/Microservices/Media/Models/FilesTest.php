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
				],
				'single_file_multi_level' => [
					'files' => [
						'files' => [
							'name' => [
								'file_1' => 'woolf-002.jpg',
								'file_2' => 'shi.txt',
								'file_3' => [
									'file_3_1' => 'woolf-001.png',
									'file_3_2' => 'woolf-003.jpeg'
								]
							],
							'type' => [
								'file_1' => ImagesExtensions::JPG,
								'file_2' => 'text/plain',
								'file_3' => [
									'file_3_1' => ImagesExtensions::PNG,
									'file_3_2' => ImagesExtensions::JPG
								]
							],
							'size' => [
								'file_1' => 1024,
								'file_2' => 2048,
								'file_3' => [
									'file_3_1' => 3072,
									'file_3_2' => 4096
								]
							],
							'tmp_name' => [
								'file_1' => '/tmp/phpABC123',
								'file_2' => '/tmp/phpDEF456',
								'file_3' => [
									'file_3_1' => '/tmp/phpGHI789',
									'file_3_2' => '/tmp/phpJKL012'
								]
							],
							'error' => [
								'file_1' => UPLOAD_ERR_OK,
								'file_2' => UPLOAD_ERR_OK,
								'file_3' => [
									'file_3_1' => UPLOAD_ERR_OK,
									'file_3_2' => UPLOAD_ERR_OK
								]
							]
						]
					],
					'expected' => [
						'[files][file_1]' => [
							'name' => 'woolf-002.jpg',
							'type' => ImagesExtensions::JPG,
							'size' => 1024,
							'tmp_name' => '/tmp/phpABC123',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_2]' => [
							'name' => 'shi.txt',
							'type' => 'text/plain',
							'size' => 2048,
							'tmp_name' => '/tmp/phpDEF456',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_3][file_3_1]' => [
							'name' => 'woolf-001.png',
							'type' => ImagesExtensions::PNG,
							'size' => 3072,
							'tmp_name' => '/tmp/phpGHI789',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_3][file_3_2]' => [
							'name' => 'woolf-003.jpeg',
							'type' => ImagesExtensions::JPG,
							'size' => 4096,
							'tmp_name' => '/tmp/phpJKL012',
							'error' => UPLOAD_ERR_OK
						]
					]
				],
				'multiple_files_multi_level' => [
					'files' => [
						'files' => [
							'name' => [
								'file_1' => [
									'file_1_1' => 'woolf-001.jpg',
									'file_1_2' => 'shi.txt',
									'file_1_3' => [
										'file_1_3_1' => 'woolf-002.png',
										'file_1_3_2' => 'woolf-003.jpeg'
									]
								],
								'file_2' => [
									'file_2_1' => 'woolf-004.jpg',
									'file_2_2' => 'shi2.txt',
									'file_2_3' => [
										'file_2_3_1' => 'woolf-005.png',
										'file_2_3_2' => 'woolf-006.jpeg'
									]
								]
							],
							'type' => [
								'file_1' => [
									'file_1_1' => ImagesExtensions::JPG,
									'file_1_2' => 'text/plain',
									'file_1_3' => [
										'file_1_3_1' => ImagesExtensions::PNG,
										'file_1_3_2' => ImagesExtensions::JPG
									]
								],
								'file_2' => [
									'file_2_1' => ImagesExtensions::JPG,
									'file_2_2' => 'text/plain',
									'file_2_3' => [
										'file_2_3_1' => ImagesExtensions::PNG,
										'file_2_3_2' => ImagesExtensions::JPG
									]
								]
							],
							'size' => [
								'file_1' => [
									'file_1_1' => 1024,
									'file_1_2' => 2048,
									'file_1_3' => [
										'file_1_3_1' => 3072,
										'file_1_3_2' => 4096
									]
								],
								'file_2' => [
									'file_2_1' => 5120,
									'file_2_2' => 6144,
									'file_2_3' => [
										'file_2_3_1' => 7168,
										'file_2_3_2' => 8192
									]
								]
							],
							'tmp_name' => [
								'file_1' => [
									'file_1_1' => '/tmp/phpABC123',
									'file_1_2' => '/tmp/phpDEF456',
									'file_1_3' => [
										'file_1_3_1' => '/tmp/phpGHI789',
										'file_1_3_2' => '/tmp/phpJKL012'
									]
								],
								'file_2' => [
									'file_2_1' => '/tmp/phpMNO345',
									'file_2_2' => '/tmp/phpPQR678',
									'file_2_3' => [
										'file_2_3_1' => '/tmp/phpSTU901',
										'file_2_3_2' => '/tmp/phpVWX234'
									]
								]
							],
							'error' => [
								'file_1' => [
									'file_1_1' => UPLOAD_ERR_OK,
									'file_1_2' => UPLOAD_ERR_OK,
									'file_1_3' => [
										'file_1_3_1' => UPLOAD_ERR_OK,
										'file_1_3_2' => UPLOAD_ERR_OK
									]
								],
								'file_2' => [
									'file_2_1' => UPLOAD_ERR_OK,
									'file_2_2' => UPLOAD_ERR_OK,
									'file_2_3' => [
										'file_2_3_1' => UPLOAD_ERR_OK,
										'file_2_3_2' => UPLOAD_ERR_OK
									]
								]
							]
						]
					],
					'expected' => [
						'[files][file_1][file_1_1]' => [
							'name' => 'woolf-001.jpg',
							'type' => ImagesExtensions::JPG,
							'size' => 1024,
							'tmp_name' => '/tmp/phpABC123',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_1][file_1_2]' => [
							'name' => 'shi.txt',
							'type' => 'text/plain',
							'size' => 2048,
							'tmp_name' => '/tmp/phpDEF456',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_1][file_1_3][file_1_3_1]' => [
							'name' => 'woolf-002.png',
							'type' => ImagesExtensions::PNG,
							'size' => 3072,
							'tmp_name' => '/tmp/phpGHI789',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_1][file_1_3][file_1_3_2]' => [
							'name' => 'woolf-003.jpeg',
							'type' => ImagesExtensions::JPG,
							'size' => 4096,
							'tmp_name' => '/tmp/phpJKL012',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_2][file_2_1]' => [
							'name' => 'woolf-004.jpg',
							'type' => ImagesExtensions::JPG,
							'size' => 5120,
							'tmp_name' => '/tmp/phpMNO345',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_2][file_2_2]' => [
							'name' => 'shi2.txt',
							'type' => 'text/plain',
							'size' => 6144,
							'tmp_name' => '/tmp/phpPQR678',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_2][file_2_3][file_2_3_1]' => [
							'name' => 'woolf-005.png',
							'type' => ImagesExtensions::PNG,
							'size' => 7168,
							'tmp_name' => '/tmp/phpSTU901',
							'error' => UPLOAD_ERR_OK
						],
						'[files][file_2][file_2_3][file_2_3_2]' => [
							'name' => 'woolf-006.jpeg',
							'type' => ImagesExtensions::JPG,
							'size' => 8192,
							'tmp_name' => '/tmp/phpVWX234',
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

		public function buildFilesAllTypesProvider(): array {
			return [
				
			];
		}
	}
