<?php
	namespace DigitalSplash\Helpers;

	class Style {
		protected static $filesArray = [];
		protected static $stylesArray = [];

		public static function AddFile(
			string $file,
			$key=""
		): void {
			if (!Helper::IsNullOrEmpty($key)) {
				self::$filesArray[$key] = $file;
				return;
			}
			self::$filesArray[] = $file;
		}

		public static function RemoveFile(
			$key
		): void {
			if (isset(self::$filesArray[$key])) {
				unset(self::$filesArray[$key]);
			}
		}

		public static function GetFiles(): array {
			return self::$filesArray;
		}

		public static function ClearFiles(): void {
			self::$filesArray = [];
		}

		public static function AddStyle(
			string $style,
			$key=""
		): void {
			if (!Helper::IsNullOrEmpty($key)) {
				self::$stylesArray[$key] = $style;
				return;
			}
			self::$stylesArray[] = $style;
		}

		public static function RemoveStyle(
			$key
		): void {
			if (isset(self::$stylesArray[$key])) {
				unset(self::$stylesArray[$key]);
			}
		}

		public static function GetStyles(): array {
			return self::$stylesArray;
		}

		public static function ClearStyles(): void {
			self::$stylesArray = [];
		}

		public static function GetFilesIncludes(): string {
			$html = [];

			$files = self::GetFiles();
			foreach ($files AS $file) {
				$html[] = "<link rel=\"stylesheet\" href=\"$file\">";
			}

			$styles = self::GetStyles();
			foreach ($styles AS $style) {
				$html[] = $style;
			}

			return Helper::ImplodeArrToStr("\n", $html);
		}

	}
