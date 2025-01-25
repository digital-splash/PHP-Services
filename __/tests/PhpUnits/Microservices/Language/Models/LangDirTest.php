<?php

	namespace DigitalSplash\Tests\Language\Models;

	use DigitalSplash\Language\Models\Lang;
	use DigitalSplash\Microservices\Language\Models\Direction;
	use PHPUnit\Framework\TestCase;

	final class LangDirTest extends TestCase {
		public function testGetDirByLanguageSuccess(): void {
			$this->assertEquals(
				Direction::LTR,
				Direction::GetDirByLanguage(Lang::EN)
			);

			$this->assertEquals(
				Direction::LTR,
				Direction::GetDirByLanguage(Lang::FR)
			);

			$this->assertEquals(
				Direction::RTL,
				Direction::GetDirByLanguage(Lang::AR)
			);

			$this->assertEquals(
				"",
				Direction::GetDirByLanguage("test")
			);
		}
	}
