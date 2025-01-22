<?php
	namespace DigitalSplash\Media\Models;

use DigitalSplash\Helpers\Helper;

	class Image {
		public const ORIGINAL_PATH = '{path}/original/';

		public const THUMBNAIL_CODE = "th";
		public const THUMBNAIL_WIDTH = 128;
		public const THUMBNAIL_PATH = '{path}/th/';

		public const LOW_DEF_CODE = "ld";
		public const LOW_DEF_WIDTH = 640;
		public const LOW_DEF_PATH = '{path}/ld/';

		public const HIGH_DEF_CODE = "hd";
		public const HIGH_DEF_WIDTH = 1280;
		public const HIGH_DEF_PATH = '{path}/hd/';

		public static function getArray(array $codes = []): array {
			$arrayToReturn = [
				Image::THUMBNAIL_CODE => [
					'width' => Image::THUMBNAIL_WIDTH,
					'code' => Image::THUMBNAIL_CODE,
					'path' => Image::THUMBNAIL_PATH
				],
				Image::LOW_DEF_CODE => [
					'width' => Image::LOW_DEF_WIDTH,
					'code' => Image::LOW_DEF_CODE,
					'path' => Image::LOW_DEF_PATH
				],
				Image::HIGH_DEF_CODE => [
					'width' => Image::HIGH_DEF_WIDTH,
					'code' => Image::HIGH_DEF_CODE,
					'path' => Image::HIGH_DEF_PATH
				]
			];

			if (!Helper::IsNullOrEmpty($codes)) {
				$arrayToReturn = array_filter($arrayToReturn, function($key) use ($codes) {
					return in_array($key, $codes);
				}, ARRAY_FILTER_USE_KEY);
			}

			return $arrayToReturn;
		}
	}
