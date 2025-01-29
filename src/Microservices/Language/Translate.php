<?php

	namespace DigitalSplash\Microservices\Language;

	use DigitalSplash\Helpers\Helper;

	class Translate {
		private static bool $initialized = false;
		private static array $mappings = [];

		public static function init(): void {
			if (self::$initialized) {
				return;
			}

			self::addTranslationMapping(__DIR__ . '/Translations');

			self::$initialized = true;
		}

		/**
		 * Return the Translation of the Given Key in the given Language
		 */
		public static function get(
			?string $key,
			array   $replace = [],
			?string $lang = null,
			bool    $returnEmpty = false
		): string {
			if (Helper::isNullOrEmpty($key)) {
				return '';
			}

			if (Helper::isNullOrEmpty($lang)) {
				$lang = Language::activeCode();
			}

			if (Helper::isNullOrEmpty(self::$mappings)) {
				self::init();
			}

			$value = self::$mappings[$key][$lang] ?? self::$mappings[$key][Language::$default] ?? $key;
			if ($returnEmpty && $value === $key) {
				$value = '';
			}

			if (!Helper::isNullOrEmpty($value) && !Helper::isNullOrEmpty($replace)) {
				$value = str_replace(
					array_keys($replace),
					array_values($replace),
					$value
				);
			}

			return $value;
		}

		public static function toUpper(string $val): string {
			return str_replace([
				'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï',
			], [
				'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï',
			], strtoupper($val));
		}

		/**
		 * Add all the translations from the provided directory
		 */
		public static function addTranslationMapping(string $dir): void {
			if (Helper::isNullOrEmpty($dir) || !is_dir($dir)) {
				return;
			}

			$filesArr = Helper::getAllFiles($dir, true);
			foreach ($filesArr as $filePath) {
				self::addFileContentToMappings($dir, $filePath);
			}
		}

		private static function addFileContentToMappings(string $dir, string $filePath): void {
			[
				'basename' => $basename,
				'extension' => $extension,
				'filename' => $filename,
			] = pathinfo($filePath);

			if ($extension !== 'json') {
				return;
			}

			$dirFolders = str_replace($basename, '', str_replace($dir, '', $filePath));
			$dirFoldersArr = Helper::explode('/', $dirFolders);

			$pre = Helper::implode('.', [
					Helper::implode('.', $dirFoldersArr),
					$filename,
				]) . '.';

			$fileMappings = Helper::addPrefixToArrayKeys(
				Helper::getJsonContentFromFileAsArray($filePath),
				$pre
			);

			self::$mappings = array_merge(self::$mappings, $fileMappings);
		}
	}
