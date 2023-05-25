<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\FileNotFoundException;
	use DigitalSplash\Exceptions\Media\InvalidExtensionException;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Models\DocumentsExtensions;
	use DigitalSplash\Media\Models\Image;
	use DigitalSplash\Media\Models\ImagesExtensions;

	class Media {
		private static $MEDIA_FOLDER = "mediafiles/";
		private static $UPLOAD_DIR;
		private static $MEDIA_ROOT;
		private static $WEBSITE_VERSION;

		/**
		 * Set the $MEDIA_FOLDER valiable
		 */
		public static function SetMediaFolder(
			string $var
		): void {
			self::$MEDIA_FOLDER = $var;
		}

		/**
		 * Set the $UPLOAD_DIR valiable
		 */
		public static function SetUploadDir(
			string $var
		): void {
			if (!Helper::StringEndsWith($var, ["/", "\\"])) {
				$var .= "/";
			}
			self::$UPLOAD_DIR = $var;
		}

		public static function GetUploadDir(): ?string {
			return self::$UPLOAD_DIR;
		}

		/**
		 * Set the $MEDIA_ROOT valiable
		 */
		public static function SetMediaRoot(
			string $var
		): void {
			if (!Helper::StringEndsWith($var, ["/", "\\"])) {
				$var .= "/";
			}
			self::$MEDIA_ROOT = $var;
		}

		/**
		 * Set the $WEBSITE_VERSION valiable
		 */
		public static function SetWebsiteVersion(
			string $var
		): void {
			self::$WEBSITE_VERSION = $var;
		}

		/**
		 * Adds the root folder to a url, and converts it to a safe, user friendly URL
		 * @param string $path
		 * @return string
		 */
		public static function GetMediaFullPath(
			?string $path=null,
			?string $imageCode=null,
			bool $getNextGen=false,
			bool $withVersion=true,
			bool $withDomain=true
		): string {
			if (Helper::IsNullOrEmpty(self::$UPLOAD_DIR)) {
				throw new NotEmptyParamException("UPLOAD_DIR");
			}
			if (Helper::IsNullOrEmpty(self::$MEDIA_ROOT)) {
				throw new NotEmptyParamException("MEDIA_ROOT");
			}
			if (Helper::IsNullOrEmpty($path)) {
				throw new NotEmptyParamException("path");
			}

			$path = str_replace(self::$MEDIA_FOLDER, "", $path);
			[
				"dirname" => $dirName,
				"basename" => $baseName,
				"filename" => $fileName,
				"extension" => $extension,
			] = pathinfo($path);
			if ($getNextGen) {
				$newExtension = "webp";
				$path = str_replace(".{$extension}", ".{$newExtension}", $path);
				$extension = $newExtension;
			}

			if (!Helper::IsNullOrEmpty($imageCode)) {
				$imageCodes = [];
				if ($imageCode === Image::THUMBNAIL_CODE) {
					$imageCodes = [
						Image::THUMBNAIL_CODE,
						Image::LOW_DEF_CODE,
						Image::HIGH_DEF_CODE
					];
				}
				else if ($imageCode === Image::LOW_DEF_CODE) {
					$imageCodes = [
						Image::LOW_DEF_CODE,
						Image::HIGH_DEF_CODE
					];
				}
				else if ($imageCode === Image::HIGH_DEF_CODE) {
					$imageCodes = [
						Image::HIGH_DEF_CODE
					];
				}
				else {
					$imageCodes = [$imageCode];
				}

				$options = [];
				foreach ($imageCodes AS $imageCode) {
					$options[] = $dirName . "/" . $fileName . "-" . $imageCode . "." . $extension;
				}
			}
			$options[] = $dirName . "/" . $fileName . "." . $extension;

			$url = "";
			foreach ($options AS $option) {
				if (file_exists(self::$UPLOAD_DIR . $option)) {
					$url = self::$MEDIA_ROOT . $option;
					break;
				}
			}

			if (Helper::IsNullOrEmpty($url)) {
				throw new FileNotFoundException(self::$MEDIA_ROOT . $path);
			}

			if ($withVersion && !Helper::IsNullOrEmpty(self::$WEBSITE_VERSION)) {
				$url .= "?v=" . self::$WEBSITE_VERSION;
			}

			if (!$withDomain) {
				$url = str_replace(self::$MEDIA_ROOT, "", $url);
			}

			return $url;
		}

		public static function IsExtension(string $extension, array $allowedExtensions): bool {
			return in_array($extension, $allowedExtensions);
		}

		public static function IsImage(string $extension): bool {
			return self::IsExtension($extension, ImagesExtensions::getExtensions());
		}

		public static function IsDocument(string $extension): bool {
			return self::IsExtension($extension, DocumentsExtensions::getExtensions());
		}

		public static function validateIsExtension(string $extension, array $allowedExtensions): void {
			if (!self::IsExtension($extension, $allowedExtensions)) {
				$allowed = implode(", ", $allowedExtensions);
				throw new InvalidExtensionException($allowed);
			}
		}

		public static function validateIsImage(string $extension): void {
			self::validateIsExtension($extension, ImagesExtensions::getExtensions());
		}

		public static function validateIsDocument(string $extension): void {
			self::validateIsExtension($extension, DocumentsExtensions::getExtensions());
		}

	}
