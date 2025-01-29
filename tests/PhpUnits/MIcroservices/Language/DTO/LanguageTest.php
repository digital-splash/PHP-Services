<?php

	namespace DigitalSplash\Tests\Microservices\Language\DTO;

	use DigitalSplash\Microservices\Language\DTO\Language;
	use PHPUnit\Framework\TestCase;

	final class LanguageTest extends TestCase {
		public function testToArray(): void {
			$arr = [
				'code' => 'en',
				'name' => 'English',
				'direction' => 'ltr',
				'isActive' => true,
			];

			$language = Language::arrayDeserialize($arr);

			$arr['isDefault'] = false;
			$this->assertEquals($arr, $language->toArray());
		}
	}