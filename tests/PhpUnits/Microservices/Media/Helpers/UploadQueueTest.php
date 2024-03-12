<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Helpers\Media;
	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Helpers\UploadQueue;

	class UploadQueueTest extends TestCase {
		private const DIR = __DIR__ . "/../../../../_CommonFiles/Media/users/profile";

		public function testProcessImages(): void {
			// $this->markTestSkipped('This test is skipped because it is working on its own but not in bulk.');
			Helper::CreateFolderRecursive(self::DIR . "/og");
			copy(
				self::DIR . "/user-02.jpg",
				self::DIR . "/og/user-02.jpg"
			);
			copy(
				self::DIR . "/user-02-th.jpg",
				self::DIR . "/og/user-02-th.jpg"
			);
			Media::SetUploadDir(self::DIR . "/og");

			$uploadQueue = new UploadQueue($_FILES, 'test-upload-queue', '///', [], 5, true, true, ['all']);
			$uploadQueue->processImages([
				self::DIR . "/og/user-02.jpg",
				self::DIR . "/og/user-02-th.jpg",
			]);

			$assertions = [
				self::DIR . "/og/hd/user-02-th.webp",
				self::DIR . "/og/hd/user-02.webp",
				self::DIR . "/og/ld/user-02-th.webp",
				self::DIR . "/og/ld/user-02.webp",
				self::DIR . "/og/th/user-02-th.webp",
				self::DIR . "/og/th/user-02.webp",
				self::DIR . "/og/fb/cover/user-02-th.webp",
				self::DIR . "/og/fb/cover/user-02.webp",
				self::DIR . "/og/fb/post/user-02-th.webp",
				self::DIR . "/og/fb/post/user-02.webp",
				self::DIR . "/og/fb/profile/user-02-th.webp",
				self::DIR . "/og/fb/profile/user-02.webp",
				self::DIR . "/og/user-02-th.webp",
				self::DIR . "/og/user-02.webp"
			];

			foreach ($assertions as $assertion) {
				$this->assertFileExists($assertion);
			}

			//remove all created directories
			Helper::DeleteFoldersAndFiles(self::DIR . "/og" , true);
		}
	}
