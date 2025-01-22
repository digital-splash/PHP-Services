<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Exceptions\FileNotFoundException;
	use DigitalSplash\Exceptions\Media\InvalidExtensionException;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Language\Helpers\Translate;
	use DigitalSplash\Media\Helpers\Media;
	use DigitalSplash\Media\Models\DocumentsExtensions;
	use DigitalSplash\Media\Models\Image;
	use DigitalSplash\Media\Models\ImagesExtensions;

	final class MediaTest extends TestCase {
		private const MEDIA_ROOT = "https://media.domain.com";
		private const WEBSITE_VERSION = "2.0.1";

		public function setUp(): void {
			Media::SetUploadDir(__DIR__ . "/../../../../_CommonFiles/Media");
			Media::SetMediaRoot(self::MEDIA_ROOT);
			Media::SetWebsiteVersion(self::WEBSITE_VERSION);

			parent::setUp();
		}

		public function testGetMediaFullPathThrowNotEmptyError_01(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "path"
			]));
			Media::GetMediaFullPath("");
		}

		public function testGetMediaFullPathThrowNotEmptyError_02(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "path"
			]));
			Media::GetMediaFullPath(null);
		}

		public function testGetMediaFullPathThrowFileNotFoundError(): void {
			$this->expectException(FileNotFoundException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.FileNotFound", null, [
				"::params::" => self::MEDIA_ROOT . "/users/profile/invalid-user-01.jpg"
			]));
			Media::GetMediaFullPath("mediafiles/users/profile/invalid-user-01.jpg");
		}

		public function testGetMediaFullPathSuccess(): void {
			$this->assertEquals(
				self::MEDIA_ROOT . "/users/profile/user-01.jpg?v=" . self::WEBSITE_VERSION,
				Media::GetMediaFullPath("mediafiles/users/profile/user-01.jpg")
			);

			$this->assertEquals(
				self::MEDIA_ROOT . "/users/profile/user-01-th.jpg?v=" . self::WEBSITE_VERSION,
				Media::GetMediaFullPath("mediafiles/users/profile/user-01.jpg", Image::THUMBNAIL_CODE)
			);

			$this->assertEquals(
				self::MEDIA_ROOT . "/users/profile/user-01.jpg?v=" . self::WEBSITE_VERSION,
				Media::GetMediaFullPath("mediafiles/users/profile/user-01.jpg", Image::HIGH_DEF_CODE)
			);

			$this->assertEquals(
				self::MEDIA_ROOT . "/users/profile/user-01.webp?v=" . self::WEBSITE_VERSION,
				Media::GetMediaFullPath("mediafiles/users/profile/user-01.jpg", null, true)
			);

			$this->assertEquals(
				self::MEDIA_ROOT . "/users/profile/user-01.jpg",
				Media::GetMediaFullPath("mediafiles/users/profile/user-01.jpg", null, false, false)
			);

			$this->assertEquals(
				"users/profile/user-01.jpg",
				Media::GetMediaFullPath("mediafiles/users/profile/user-01.jpg", null, false, false, false)
			);
		}

		public function testIsExtension(): void {
			$this->assertTrue(Media::IsExtension('test', [
				'test',
				'test1',
				'test2'
			]));

			$this->assertFalse(Media::IsExtension('test', [
				'test1',
				'test2'
			]));
		}

		public function testIsImage(): void {
			$this->assertTrue(Media::IsImage(ImagesExtensions::JPG));
			$this->assertFalse(Media::IsImage('test'));
		}

		public function testIsDocument(): void {
			$this->assertTrue(Media::IsDocument(DocumentsExtensions::PDF));
			$this->assertFalse(Media::IsDocument('test'));
		}

		public function testValidateIsExtension(): void {
			Media::validateIsExtension('test', [
				'test',
				'test1',
				'test2'
			]);

			$this->expectException(InvalidExtensionException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.media.InvalidExtension", null, [
				"::params::" => "test1, test2"
			]));

			Media::validateIsExtension('test', [
				'test1',
				'test2'
			]);
		}

		public function testValidateIsImage(): void {
			Media::validateIsImage(ImagesExtensions::JPG);

			$this->expectException(InvalidExtensionException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.media.InvalidExtension", null, [
				"::params::" => Helper::ImplodeArrToStr(', ', ImagesExtensions::getExtensions())
			]));

			Media::validateIsImage('test');
		}

		public function testValidateIsDocument(): void {
			Media::validateIsDocument(DocumentsExtensions::PDF);

			$this->expectException(InvalidExtensionException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.media.InvalidExtension", null, [
				"::params::" => Helper::ImplodeArrToStr(', ', DocumentsExtensions::getExtensions())
			]));

			Media::validateIsDocument('test');
		}

	}
