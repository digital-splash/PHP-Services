<?php
	namespace DigitalSplash\Tests\Media\Models;

	use DigitalSplash\Media\Models\Image;
	use PHPUnit\Framework\TestCase;

	class ImageTest extends TestCase {

		public function testGetArray(): void {
			$this->assertEquals(
				[
					Image::THUMBNAIL_CODE => [
						'width' => Image::THUMBNAIL_WIDTH,
						'code' => Image::THUMBNAIL_CODE,
						'path' => Image::THUMBNAIL_PATH
					],
					Image::LOW_DEF_CODE => [
						'width' => Image::LOW_DEF_WIDTH,
						'code' => Image::LOW_DEF_CODE,
						'path' => Image::LOW_DEF_PATH
					],
					Image::HIGH_DEF_CODE => [
						'width' => Image::HIGH_DEF_WIDTH,
						'code' => Image::HIGH_DEF_CODE,
						'path' => Image::HIGH_DEF_PATH
					]
				],
				Image::getArray()
			);
		}

		public function testGetArrayWithCodes(): void {
			$this->assertEquals(
				[
					Image::THUMBNAIL_CODE => [
						'width' => Image::THUMBNAIL_WIDTH,
						'code' => Image::THUMBNAIL_CODE,
						'path' => Image::THUMBNAIL_PATH
					],
					Image::LOW_DEF_CODE => [
						'width' => Image::LOW_DEF_WIDTH,
						'code' => Image::LOW_DEF_CODE,
						'path' => Image::LOW_DEF_PATH
					]
				],
				Image::getArray([Image::THUMBNAIL_CODE, Image::LOW_DEF_CODE])
			);
		}
	}