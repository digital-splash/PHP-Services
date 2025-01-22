<?php
	namespace DigitalSplash\Tests\Utils;

	use DigitalSplash\Models\RequestMethod;

	class BasicTestUtils {

		public static function setMethod(string $method): void {
			$_SERVER['REQUEST_METHOD'] = $method;
		}

		public static function setMethodPost(): void {
			self::setMethod(RequestMethod::POST);
		}

		public static function setMethodGet(): void {
			self::setMethod(RequestMethod::GET);
		}

		public static function setMethodPut(): void {
			self::setMethod(RequestMethod::PUT);
		}

		public static function setMethodPatch(): void {
			self::setMethod(RequestMethod::PATCH);
		}

		public static function setMethodDelete(): void {
			self::setMethod(RequestMethod::DELETE);
		}
	}
