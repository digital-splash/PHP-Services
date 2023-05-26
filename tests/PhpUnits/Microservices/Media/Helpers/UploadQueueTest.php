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

			$assertions = [
				self::DIR . "/og/hd/user-01-th.webp",
				self::DIR . "/og/hd/user-01.webp",
				self::DIR . "/og/ld/user-01-th.webp",
				self::DIR . "/og/ld/user-01.webp",
				self::DIR . "/og/th/user-01-th.webp",
				self::DIR . "/og/th/user-01.webp",
				self::DIR . "/og/fb/cover/user-01-th.webp",
				self::DIR . "/og/fb/cover/user-01.webp",
				self::DIR . "/og/fb/post/user-01-th.webp",
				self::DIR . "/og/fb/post/user-01.webp",
				self::DIR . "/og/fb/profile/user-01-th.webp",
				self::DIR . "/og/fb/profile/user-01.webp",
				self::DIR . "/og/user-01-th.webp",
				self::DIR . "/og/user-01.webp"
			];

			foreach ($assertions as $assertion) {
				$this->assertFileExists($assertion);
			}

			//remove all created directories
			Helper::DeleteFoldersAndFiles(self::DIR . "/og" , true);
		}
	}
