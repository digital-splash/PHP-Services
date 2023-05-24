<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Helpers\Media;
	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Helpers\UploadQueue;

	class UploadQueueTest extends TestCase {

		public function testProcessImages(): void {
			Helper::CreateFolderRecursive(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og");
			copy(
				__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.jpg",
				__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/user-01.jpg"
			);
			copy(
				__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.jpg",
				__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/user-01-th.jpg"
			);
			Media::SetUploadDir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og");

			$uploadQueue = new UploadQueue($_FILES, 'test-upload-queue', '///', [], 5, true, true, ['all']);
				$uploadQueue->processImages([
					__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/user-01.jpg",
					__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/user-01-th.jpg",
				]);
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/hd/user-01-th.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/hd/user-01-th.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/hd/user-01.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/hd/user-01.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/ld/user-01-th.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/ld/user-01-th.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/ld/user-01.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/ld/user-01.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/th/user-01-th.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/th/user-01-th.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/th/user-01.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/th/user-01.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/cover/user-01-th.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/cover/user-01-th.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/cover/user-01.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/cover/user-01.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/post/user-01-th.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/post/user-01-th.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/post/user-01.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/post/user-01.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/profile/user-01-th.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/profile/user-01-th.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/profile/user-01.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/profile/user-01.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/user-01-th.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/user-01-th.webp");
				$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/user-01.webp");
				unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/user-01.webp");

				//remove all created directories
				rmdir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/hd");
				rmdir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/ld");
				rmdir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/th");
				rmdir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/cover");
				rmdir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/post");
				rmdir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb/profile");
				rmdir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og/fb");
				rmdir(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/og");
		}
	}