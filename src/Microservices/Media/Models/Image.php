<?php
	namespace DigitalSplash\Media\Models;


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
	}
