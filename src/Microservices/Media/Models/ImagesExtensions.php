<?php
	namespace DigitalSplash\Media\Models;

	class ImagesExtensions {
		public const JPG = "jpg";
		public const JPEG = "jpeg";
		public const PNG = "png";
		public const GIF = "gif";
		public const WEBP = "webp";

		public static function getExtensions(): array {
			return [
				self::JPG,
				self::JPEG,
				self::PNG,
				self::GIF,
				self::WEBP,
			];
		}
	}
