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
			Helper::DeleteFileOrFolder(self::DIR . "/og/hd/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/hd/user-01.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/hd/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/ld/user-01-th.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/ld/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/ld/user-01.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/ld/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/th/user-01-th.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/th/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/th/user-01.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/th/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/fb/cover/user-01-th.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/cover/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/fb/cover/user-01.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/cover/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/fb/post/user-01-th.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/post/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/fb/post/user-01.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/post/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/fb/profile/user-01-th.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/profile/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/fb/profile/user-01.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/profile/user-01.webp");
			$this->assertFileExists(self::DIR . "/og/user-01-th.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/user-01-th.webp");
			$this->assertFileExists(self::DIR . "/og/user-01.webp");
			Helper::DeleteFileOrFolder(self::DIR . "/og/user-01.webp");

			//remove all created directories
			Helper::DeleteFileOrFolder(self::DIR . "/og/hd");
			Helper::DeleteFileOrFolder(self::DIR . "/og/ld");
			Helper::DeleteFileOrFolder(self::DIR . "/og/th");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/cover");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/post");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb/profile");
			Helper::DeleteFileOrFolder(self::DIR . "/og/fb");
			Helper::DeleteFileOrFolder(self::DIR . "/og");

			//TODO: Maybe Create a new Function Helper::DeleteFolderAndAllFiles(), that will loop through all the files and folder inside the given folder and deletes them and then delete the fiver folder.
		}
	}
