<?php
	namespace DigitalSplash\Helpers;

	class Script {
		protected static $filesArray = [];
		protected static $scriptsArray = [];

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

		public static function AddScript(
			string $script,
			$key=""
		): void {
			if (!Helper::IsNullOrEmpty($key)) {
				self::$scriptsArray[$key] = $script;
				return;
			}
			self::$scriptsArray[] = $script;
		}

		public static function RemoveScript(
			$key
		): void {
			if (isset(self::$scriptsArray[$key])) {
				unset(self::$scriptsArray[$key]);
			}
		}

		public static function GetScripts(): array {
			return self::$scriptsArray;
		}

		public static function ClearScripts(): void {
			self::$scriptsArray = [];
		}

		public static function GetFilesIncludes(): string {
			$html = [];

			$files = self::GetFiles();
			foreach ($files AS $file) {
				$html[] = "<script src=\"$file\"></script>";
			}

			$scripts = self::GetScripts();
			foreach ($scripts AS $script) {
				$html[] = $script;
			}

			return Helper::ImplodeArrToStr("\n", $html);
		}

	}
