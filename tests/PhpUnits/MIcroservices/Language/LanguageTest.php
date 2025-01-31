<?php

	namespace DigitalSplash\Tests\Microservices\Language;

	use DigitalSplash\Core\Env;
	use DigitalSplash\Microservices\Language\DTO\Language as LanguageDTO;
	use DigitalSplash\Microservices\Language\Language;
	use DigitalSplash\Microservices\Language\Models\Direction;
	use DigitalSplash\Microservices\Language\Models\Language as LanguageModel;
	use PHPUnit\Framework\TestCase;

	final class LanguageTest extends TestCase {
		public function testInitArabicAsDefault(): void {
			$langDtoEn = LanguageDTO::arrayDeserialize([
				'code' => LanguageModel::EN,
				'name' => 'English',
				'direction' => Direction::LTR,
				'isActive' => true,
				'isDefault' => false,
			]);
			$langDtoAr = LanguageDTO::arrayDeserialize([
				'code' => LanguageModel::AR,
				'name' => 'Arabic',
				'direction' => Direction::RTL,
				'isActive' => true,
				'isDefault' => true,
			]);

			Language::init([
				$langDtoEn,
				$langDtoAr,
			], true);

			$this->assertEquals(LanguageModel::AR, Language::$default);
			$this->assertEqualsCanonicalizing(json_encode($langDtoAr), json_encode(Language::$active));
			$this->assertEquals(LanguageModel::AR, Language::$activeCode);
		}

		public function testInitNotActiveLang(): void {
			$langDtoEn = LanguageDTO::arrayDeserialize([
				'code' => LanguageModel::EN,
				'name' => 'English',
				'direction' => Direction::LTR,
				'isActive' => true,
				'isDefault' => true,
			]);
			$langDtoAr = LanguageDTO::arrayDeserialize([
				'code' => LanguageModel::AR,
				'name' => 'Arabic',
				'direction' => Direction::RTL,
				'isActive' => true,
				'isDefault' => false,
			]);
			$langDtoFr = LanguageDTO::arrayDeserialize([
				'code' => LanguageModel::FR,
				'name' => 'French',
				'direction' => Direction::LTR,
				'isActive' => false,
				'isDefault' => false,
			]);

			Language::init([
				$langDtoEn,
				$langDtoAr,
				$langDtoFr,
			], true);

			$this->assertEqualsCanonicalizing([LanguageModel::EN, LanguageModel::AR], Language::$activeCodes);
		}

		public function testInitLangSwitchUrl(): void {
			$_SERVER['REQUEST_SCHEME'] = 'https';
			$_SERVER['SERVER_NAME'] = 'example.com';
			$_SERVER['REQUEST_URI'] = '/page1/page2';

			Env::init(true);
			Language::init([], true);

			$this->assertEquals('https://example.com/page1/page2/?lang={{lang}}', Language::$langSwitchUrl);
		}

//		public function testActiveFromGet

		public function testInitChangeLanguageIfInvalid(): void {
			$_GET['lang'] = 'fr';

			$langDtoAr = LanguageDTO::arrayDeserialize([
				'code' => LanguageModel::AR,
				'name' => 'Arabic',
				'direction' => Direction::RTL,
				'isActive' => true,
				'isDefault' => true,
			]);

			Language::init([$langDtoAr], true);

			$this->assertEquals(LanguageModel::AR, $_GET['lang']);
		}
	}