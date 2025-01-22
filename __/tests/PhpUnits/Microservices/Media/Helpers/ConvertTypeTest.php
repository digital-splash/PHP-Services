<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use DigitalSplash\Media\Helpers\ConvertType;
	use PHPUnit\Framework\TestCase;

	class ConvertTypeTest extends TestCase {

		public function convertTypeTestThrowsProvider(): array {
			return [
				"Not allowed extention" => [
					"params" => [
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.jpg",
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.webp",
						"bmp",
						true
					],
					"exception" => "File extension is not allowed! Allowed extensions: \"jpg, jpeg, png, gif, webp\""
				],
				"empty extension" => [
					"params" => [
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.jpg",
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.webp",
						"",
						true
					],
					"exception" => "The parameter(s) (extension) is/are invalid"
				],
				"empty source" => [
					"params" => [
						"",
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.webp",
						"webp",
						true
					],
					"exception" => "The parameter(s) (source) is/are invalid"
				],
				"empty destination" => [
					"params" => [
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.jpg",
						"",
						"webp",
						true
					],
					"exception" => "The parameter(s) (destination) is/are invalid"
				],
				"source does not exist" => [
					"params" => [
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-not-exist.jpg",
						__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01.webp",
						"webp",
						true
					],
					"exception" => "Source file does not exist!"
				],
				"empty params" => [
					"params" => [
						"",
						"",
						"",
						true
					],
					"exception" => "The parameter(s) (source, destination, extension) is/are invalid"
				]
			];
		}

		/**
		 * @dataProvider convertTypeTestThrowsProvider
		 * @param array $params
		 * @param string $exception
		 */
		public function testConvertTypeTestThrows(array $params, string $exception): void {
			$this->expectExceptionMessage($exception);
			$convertType = new ConvertType(...$params);
			$convertType->save();
		}

		public function testConvertTypeTestSuccess(): void {
			$convertType = new ConvertType(
				__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.jpg",
				__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp",
				"webp",
				true
			);
			$convertType->save();
			$this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp");
			unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp");
		}
}
