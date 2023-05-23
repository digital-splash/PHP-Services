<?php
	namespace DigitalSplash\Tests\Media\Helpers;

	use DigitalSplash\Media\Helpers\Resize;
	use PHPUnit\Framework\TestCase;

    class ResizeTest extends TestCase {

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
            unlink(__DIR__ . "/../../../../_CommonFiles/Media/users/profile/test/user-01-th.webp");
        }
    }