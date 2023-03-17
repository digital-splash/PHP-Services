<?php
	namespace DigitalSplash\Tests\Language\Models;

	//To Run: .\vendor/bin/phpunit .\tests\PhpUnits\Microservices\Language\Models\LangDirTest.php
	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Language\Models\Lang;
	use DigitalSplash\Language\Models\LangDir;

	final class LangDirTest extends TestCase {

		public function testGetDirByLanguageSuccess(): void {
			$this->assertEquals(
				LangDir::LTR,
				LangDir::GetDirByLanguage(Lang::EN)
			);

			$this->assertEquals(
				LangDir::LTR,
				LangDir::GetDirByLanguage(Lang::FR)
			);

			$this->assertEquals(
				LangDir::RTL,
				LangDir::GetDirByLanguage(Lang::AR)
			);

			$this->assertEquals(
				"",
				LangDir::GetDirByLanguage("test")
			);
		}

	}
