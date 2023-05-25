<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Helpers\Media;
	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Helpers\UploadQueue;

	class UploadQueueTest extends TestCase {
		private const DIR = __DIR__ . "/../../../../_CommonFiles/Media/users/profile";

		public function testProcessImages(): void {
			Helper::CreateFolderRecursive(self::DIR . "/og");
			copy(
				self::DIR . "/user-01.jpg",
				self::DIR . "/og/user-01.jpg"
			);
			copy(
				self::DIR . "/user-01-th.jpg",
				self::DIR . "/og/user-01-th.jpg"
			);
			Media::SetUploadDir(self::DIR . "/og");

			$uploadQueue = new UploadQueue($_FILES, 'test-upload-queue', '///', [], 5, true, true, ['all']);
			$uploadQueue->processImages([
				self::DIR . "/og/user-01.jpg",
				self::DIR . "/og/user-01-th.jpg",
			]);

			$this->assertFileExists(self::DIR . "/og/hd/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/hd/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/ld/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/ld/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/th/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/th/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/fb/cover/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/fb/cover/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/fb/post/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/fb/post/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/fb/profile/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/fb/profile/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/user-01.webp");

			sleep(10);
			//remove all created directories
			Helper::DeleteFolderAndAllFiles(self::DIR . "/og" , true);
		}
	}
