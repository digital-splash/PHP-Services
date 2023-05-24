<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use DigitalSplash\Media\Helpers\Resize;
	use Intervention\Image\ImageManager;
	use PHPUnit\Framework\TestCase;

    class ResizeTest extends TestCase {

        public function resizeTestThrowsProvider(): array {
            return [
                "empty source" => [
                    "params" => [
                        "",
                        __DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp",
                        100,
                        1.5,
                        true
                    ],
                    "exception" => "The parameter(s) (source) is/are invalid"
                ],
                "empty destination" => [
                    "params" => [
                        __DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.jpg",
                        "",
                        100,
                        1.5,
                        true
                    ],
                    "exception" => "The parameter(s) (destination) is/are invalid"
                ],
                "width less than zero" => [
                    "params" => [
                        __DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.jpg",
                        __DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp",
                        -1,
                        1.5,
                        true
                    ],
                    "exception" => "The parameter(s) (width) is/are invalid"
                ],
                "source does not exist" => [
                    "params" => [
                        __DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th-does-not-exist.jpg",
                        __DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.webp",
                        100,
                        1.5,
                        true
                    ],
                    "exception" => "Source file does not exist!"
                ],
                "params empty" => [
                    "params" => [
                        "",
                        "",
                        -1
                    ],
                    "exception" => "The parameter(s) (source, destination, width) is/are invalid"
                ]
            ];
        }

        /**
         * @dataProvider resizeTestThrowsProvider
         * @param array $params
		 * @param string $exception
         */
        public function testResizeTestThrows(array $params, string $exception): void {
            $this->expectExceptionMessage($exception);
            $resize = new Resize(...$params);
            $resize->save();
        }

        public function testResizeTestSuccess(): void {
            $resize = new Resize(
                __DIR__ . "/../../../../_CommonFiles/Media/users/profile/user-01-th.jpg",
                __DIR__ . "/../../../../_CommonFiles/Media/users/profile/test/user-01-th.jpg",
                100,
                1.5,
                true
            );
            $resize->save();
            $this->assertFileExists(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/test/user-01-th.webp");
            $manager = new ImageManager([
				'driver' => 'gd'
			]);
            $image = $manager->make(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/test/user-01-th.webp");
            $this->assertEquals(100, $image->width());
            unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/test/user-01-th.webp");
        }
    }