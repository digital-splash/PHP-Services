<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use DigitalSplash\Media\Helpers\Ratio;
	use PHPUnit\Framework\TestCase;

	class RatioTest extends TestCase {

		public function ratioThrowsProvider(): array {
			return [
				"empty source" => [
					"params" => [
						"",
						1.5,
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp",
						true
					],
					"exception" => "The parameter(s) (source) is/are invalid"
				],
				"source does not exist" => [
					"params" => [
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-not-exist.jpg",
						1.5,
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp",
						true
					],
					"exception" => "Source file does not exist!"
				],
				"not allowed extension" => [
					"params" => [
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.bmp",
						1.5,
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.bmp",
						true
					],
					"exception" => "File extension is not allowed! Allowed extensions: jpg, jpeg, png, gif, webp"
				],
			];
		}

		/**
		 * @dataProvider ratioThrowsProvider
		 * @param array $params
		 * @param string $exception
		 */
		public function testRatioThrows(array $params, string $exception): void {
			$this->expectExceptionMessage($exception);
			$ratio = new Ratio(...$params);
			$ratio->save();
		}

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