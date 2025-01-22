<?php
	namespace DigitalSplash\Tests\Media\Models;

	use DigitalSplash\Media\Models\FacebookImage;
	use PHPUnit\Framework\TestCase;

	class FacebookImageTest extends TestCase {

			public function testGetArray(): void {
				$this->assertEqualsCanonicalizing(
					[
						FacebookImage::PROFILE_CODE => [
							'width' => FacebookImage::PROFILE_WIDTH,
							'ratio' => FacebookImage::PROFILE_RATIO,
							'code' => FacebookImage::PROFILE_CODE,
							'path' => FacebookImage::PROFILE_PATH
						],
						FacebookImage::COVER_CODE => [
							'width' => FacebookImage::COVER_WIDTH,
							'ratio' => FacebookImage::COVER_RATIO,
							'code' => FacebookImage::COVER_CODE,
							'path' => FacebookImage::COVER_PATH
						],
						FacebookImage::POST_CODE => [
							'width' => FacebookImage::POST_WIDTH,
							'ratio' => FacebookImage::POST_RATIO,
							'code' => FacebookImage::POST_CODE,
							'path' => FacebookImage::POST_PATH
						]
					],
					FacebookImage::getArray(['all'])
				);
			}

			public function testGetArrayWithCodes(): void {
				$this->assertEqualsCanonicalizing(
					[
						FacebookImage::PROFILE_CODE => [
							'width' => FacebookImage::PROFILE_WIDTH,
							'ratio' => FacebookImage::PROFILE_RATIO,
							'code' => FacebookImage::PROFILE_CODE,
							'path' => FacebookImage::PROFILE_PATH
						],
						FacebookImage::COVER_CODE => [
							'width' => FacebookImage::COVER_WIDTH,
							'ratio' => FacebookImage::COVER_RATIO,
							'code' => FacebookImage::COVER_CODE,
							'path' => FacebookImage::COVER_PATH
						]
					],
					FacebookImage::getArray([FacebookImage::PROFILE_CODE, FacebookImage::COVER_CODE])
				);
			}
	}