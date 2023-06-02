<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Helpers\Helper;

	class Template {
		const TEMPLATE_PATH = __DIR__ . '/../Templates/Email/';

		private $keys = [];
		private $templatePath = '';
		private $templateContentPath = '';

		public function __construct(
			array $keys,
			string $templatePath,
			string $templateContentPath
			) {
			$this->keys = $keys;
			$this->templatePath = Helper::RemoveMultipleSlashesInUrl(self::TEMPLATE_PATH . $templatePath . '.html');
			$this->templateContentPath = Helper::RemoveMultipleSlashesInUrl(self::TEMPLATE_PATH . $templateContentPath . '.html');
		}

		public function getKeys(): array {
			return $this->keys;
		}

		public function setKeys(array $keys): void {
			$this->keys = $keys;
		}

		public function appendKey(string $key, string $value): void {
			$this->keys[$key] = $value;
		}

		public function getTemplatePath(): string {
			return $this->templatePath;
		}

		public function setTemplatePath(string $templatePath): void {
			$this->templatePath = $templatePath;
		}

		public function getTemplateContentPath(): string {
			return $this->templateContentPath;
		}

		public function setTemplateContentPath(string $templateContentPath): void {
			$this->templateContentPath = $templateContentPath;
		}

		
	}