<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Helpers\Helper;

	class Template {
		const MAIN_TEMPLATE_BOXED = 'boxed';
		const MAIN_TEMPLATE_BOXED_WITH_BUTTON = 'boxed_with_button';

		const TEMPLATE_PATH = __DIR__ . '/../Templates/Email/';

		private $replaceArray = [];
		private $templatePath = '';
		private $templateContentPath = '';

		public function __construct(
			array $replaceArray,
			string $templatePath,
			string $templateContentPath = ''
		) {
			$this->replaceArray = $replaceArray;
			$this->templatePath = Helper::RemoveMultipleSlashesInUrl(self::TEMPLATE_PATH . 'Main/' . $templatePath . '.html');
			if ($templateContentPath !== '') {
				$this->templateContentPath = Helper::RemoveMultipleSlashesInUrl(self::TEMPLATE_PATH . $templateContentPath . '.html');
			}
		}

		public function getReplaceArray(): array {
			return $this->replaceArray;
		}

		public function setReplaceArray(array $replaceArray): void {
			$this->replaceArray = $replaceArray;
		}

		public function appendToReplaceArray(string $key, string $value): void {
			$this->replaceArray[$key] = $value;
		}

		public function getTemplatePath(): string {
			return $this->templatePath;
		}

		public function setTemplatePath(string $templatePath): void {
			$this->templatePath = Helper::RemoveMultipleSlashesInUrl(self::TEMPLATE_PATH . $templatePath . '.html');;
		}

		public function getTemplateContentPath(): string {
			return $this->templateContentPath;
		}

		public function setTemplateContentPath(string $templateContentPath): void {
			$this->templateContentPath = Helper::RemoveMultipleSlashesInUrl(self::TEMPLATE_PATH . $templateContentPath . '.html');
		}

		public function getContent(): string {
			$this->fixReplaceArray();

			if (!Helper::IsNullOrEmpty($this->templateContentPath)) {
				$this->appendToReplaceArray(
					'{{email_content}}',
					Helper::getContentFromFile($this->templateContentPath, $this->replaceArray)
				);
			}

			return Helper::getContentFromFile($this->templatePath, $this->replaceArray);
		}

		private function fixReplaceArray(): void {
			$newReplaceArray = [];
			foreach ($this->replaceArray as $key => $value) {
				$newReplaceArray['{{' . $key . '}}'] = $value;
			}
			$this->setReplaceArray($newReplaceArray);
		}
	}
