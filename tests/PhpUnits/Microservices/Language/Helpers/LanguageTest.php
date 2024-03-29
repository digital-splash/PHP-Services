<?php
	namespace DigitalSplash\Tests\Language\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Language\Helpers\Language;
	use DigitalSplash\Language\Models\Lang;

	final class LanguageTest extends TestCase {

		public function setUp(): void {
			Language::ClearAllowed();

			parent::setUp();
		}

		public function testUppercaseSuccess(): void {
			$this->assertEquals(
				"JOHN DOE",
				Language::Uppercase("John Doe")
			);

			$this->assertEquals(
				"DIGITÀL SPLÂÄSH",
				Language::Uppercase("Digitàl Splâäsh")
			);
		}

		public function testGetFieldKeySuccess(): void {
			$this->assertEquals(
				"first_name",
				Language::GetFieldKey("first_name")
			);

			$this->assertEquals(
				"first_name",
				Language::GetFieldKey("first_name", Lang::EN)
			);

			$this->assertEquals(
				"first_name_ar",
				Language::GetFieldKey("first_name", Lang::AR)
			);

			$this->assertEquals(
				"first_name_fr",
				Language::GetFieldKey("first_name", Lang::FR)
			);
		}

		public function testSetDefaultSuccess(): void {
			$this->assertEquals(
				Lang::EN,
				Language::GetDefault()
			);

			Language::SetDefault(Lang::AR);

			$this->assertEquals(
				Lang::AR,
				Language::GetDefault()
			);

			Language::SetDefault(Lang::EN);
		}

		public function testSetActiveSuccess(): void {
			$this->assertEquals(
				Lang::EN,
				Language::GetActive()
			);

			Language::SetActive(Lang::AR);

			$this->assertEquals(
				Lang::AR,
				Language::GetActive()
			);

			Language::SetActive(Lang::EN);
		}

		public function testAddToAllowedSuccess() {
			$this->assertEmpty(Language::GetAllowed());

			Language::AddToAllowed(Lang::EN);
			$this->assertCount(1, Language::GetAllowed());

			Language::AddToAllowed(Lang::AR);
			$this->assertCount(2, Language::GetAllowed());

			Language::AddToAllowed(Lang::EN);
			$this->assertCount(2, Language::GetAllowed());
		}

		public function testRemoveFromAllowedSuccess() {
			$this->assertEmpty(Language::GetAllowed());

			Language::AddToAllowed(Lang::EN);
			Language::AddToAllowed(Lang::AR);
			Language::AddToAllowed(Lang::FR);
			$this->assertCount(3, Language::GetAllowed());

			Language::RemoveFromAllowed(Lang::FR);
			$this->assertCount(2, Language::GetAllowed());

			Language::RemoveFromAllowed(Lang::FR);
			$this->assertCount(2, Language::GetAllowed());
		}

	}
