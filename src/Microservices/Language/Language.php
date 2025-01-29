<?php

	namespace DigitalSplash\Microservices\Language;

	use DigitalSplash\Core\Env;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Microservices\Language\DTO\Language as LanguageDto;
	use DigitalSplash\Microservices\Language\Models\Direction;
	use DigitalSplash\Microservices\Language\Models\Language as LanguageModel;

	class Language {
		private static bool $initialized = false;
		public static ?string $default = null;
		public static ?LanguageDto $active = null;
		public static ?string $activeCode = null;
		public static ?string $langSwitchUrl = null;
		/**
		 * @var LanguageDto[]
		 */
		public static array $activeLanguages = [];
		/**
		 * @var string[]
		 */
		public static array $activeCodes = [];

		/**
		 * @param LanguageDto[] $languages
		 */
		public static function init(array $languagesDto = []): void {
			if (self::$initialized) {
				return;
			}

			self::$default = LanguageModel::EN;
			self::$activeLanguages = [];

			if (empty($languagesDto)) {
				$languagesDto = self::getDefaultLanguages();
			}

			foreach ($languagesDto as $languageDto) {
				$isActive = $languageDto->isActive;
				if (!$isActive) {
					continue;
				}

				$code = $languageDto->code;
				$isDefault = $languageDto->isDefault;

				if ($isDefault) {
					self::$default = $code;
				}

				self::$activeLanguages[$code] = $languageDto;
			}

			self::$activeCodes = array_keys(self::$activeLanguages);

			self::changeLanguageIfInvalid();

			self::$active = self::active();
			self::$activeCode = self::activeCode();

			self::getLangSwitchUrl();
			self::$initialized = true;
		}

		//TODO: Use Cooke once class is created
		public static function active(): LanguageDto {
			if (!Helper::isNullOrEmpty(self::$active)) {
				return self::$active;
			}

//			$langCookie = new Cookie('lang');
			$codeChecks = [
				$_GET['lang'] ?? '', //Check Language from URL Parameter
//				$langCookie->GetCookie() ?? '', //Check Language from Cookie
				self::$active->code ?? self::$default,
				self::$default,
			];

			foreach ($codeChecks as $code) {
				if (!Helper::isNullOrEmpty($code) && array_key_exists($code, self::$activeLanguages)) {
//					$langCookie->SetCookie($langCheck);
					return self::$activeLanguages[$code] ?? self::$activeLanguages[self::$default];
				}
			}

			return self::$activeLanguages[self::$default];
		}

		public static function activeCode(): string {
			if (Helper::isNullOrEmpty(self::$active)) {
				self::init([]);
			}

			return self::$active->code;
		}

		private static function changeLanguageIfInvalid(): void {
			if (
				array_key_exists('lang', $_GET)
				&& !in_array($_GET['lang'], self::$activeCodes)
			) {
				$_GET['lang'] = self::$default;
			}
		}

		private static function getLangSwitchUrl(): void {
			$params = $_GET;
			$params['lang'] = '{{lang}}';

			self::$langSwitchUrl = Env::$urlNoParams . '?' . http_build_query($params);
		}

		private static function getDefaultLanguages(): array {
			return [
				new LanguageDto(
					LanguageModel::EN,
					'English',
					Direction::LTR,
					true,
					true
				),
			];
		}
	}
