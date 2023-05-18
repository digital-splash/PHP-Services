<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use DigitalSplash\Media\Helpers\Ratio;
	use PHPUnit\Framework\TestCase;

	class RatioTest extends TestCase {

		public function testRatioTestSuccess(): void {
			$ratio = new Ratio(
				__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.jpg",
				1.5,
				__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp",
				true
			);
			$ratio->save();
			$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp");
			unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp");
		}
	}