<?php
	namespace DigitalSplash\Tests\Media\Helpers;

use DigitalSplash\Media\Helpers\ConvertType;
use PHPUnit\Framework\TestCase;

	class ConvertTypeTest extends TestCase
{
    public function testConvertTypeTestThrows()
    {
		$this->expectException(\DigitalSplash\Exceptions\UploadException::class);
		$this->expectExceptionMessage("File extension is not allowed! Allowed extensions: jpg, jpeg, png, gif, webp");
		$convertType = new ConvertType(
			__DIR__ . "/../../../../_CommonFiles/Media/mediafiles/users/profile/user-01.jpg",
			__DIR__ . "/../../../../_CommonFiles/Media/mediafiles/users/profile/user-01.webp",
			"bmp"
		);
		$convertType->save();
    }
}