<?php

	namespace DigitalSplash\Media\Models;

	use DigitalSplash\Helpers\Helper;

	class FacebookImage {
		public const PROFILE_CODE = "profile";
		public const PROFILE_WIDTH = 170;
		public const PROFILE_RATIO = 1;
		public const PROFILE_PATH = '{path}/fb/profile/';
		public const COVER_CODE = "cover";
		public const COVER_WIDTH = 1200;
		public const COVER_RATIO = 1.9;
		public const COVER_PATH = '{path}/fb/cover/';
		public const POST_CODE = "post";
		public const POST_WIDTH = 1200;
		public const POST_RATIO = 1.9;
		public const POST_PATH = '{path}/fb/post/';

		public static function getArray(array $codes = []): array {
			if (Helper::IsNullOrEmpty($codes)) {
				return [];
			}

			$arrayToReturn = [
				FacebookImage::PROFILE_CODE => [
					'width' => FacebookImage::PROFILE_WIDTH,
					'ratio' => FacebookImage::PROFILE_RATIO,
					'code' => FacebookImage::PROFILE_CODE,
					'path' => FacebookImage::PROFILE_PATH,
				],
				FacebookImage::COVER_CODE => [
					'width' => FacebookImage::COVER_WIDTH,
					'ratio' => FacebookImage::COVER_RATIO,
					'code' => FacebookImage::COVER_CODE,
					'path' => FacebookImage::COVER_PATH,
				],
				FacebookImage::POST_CODE => [
					'width' => FacebookImage::POST_WIDTH,
					'ratio' => FacebookImage::POST_RATIO,
					'code' => FacebookImage::POST_CODE,
					'path' => FacebookImage::POST_PATH,
				],
			];

			if ($codes[0] === 'all') {
				return $arrayToReturn;
			}

			return array_filter($arrayToReturn, function($key) use ($codes) {
				return in_array($key, $codes);
			}, ARRAY_FILTER_USE_KEY);
		}
	}
