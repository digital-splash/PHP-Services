<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Helpers\Helper;

	class Template {
		// const TEMPLATE_PATH = __DIR__ . '../Templates/Email/';

		private $keys = [];

		public function __construct(array $keys) {
			$this->keys = $keys;
		}

		public function getContent(string $templateKey, array $replace = []): string {
			// $templatePath = Helper::RemoveMultipleSlashesInUrl(self::TEMPLATE_PATH . $templateKey . '.html');
			$templatePath = Helper::RemoveMultipleSlashesInUrl($templateKey);
			echo $templatePath;
			return Helper::getContentFromFile($templatePath, $replace);
		}
	}