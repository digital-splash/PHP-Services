<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Exceptions\Configuration\ConfigurationNotFoundException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Models\Tenant;

	class Template {
		const MAIN_TEMPLATE_BOXED_DEFAULT_KEY = 'boxed';
		const MAIN_TEMPLATE_BOXED_WITH_BUTTON_DEFAULT_KEY = 'boxed_with_button';

		private static string $templateSrcPath;
		private static string $templateMainSrcPath;
		private static string $templateMainNoButtonKey;
		private static string $templateMainWithButtonKey;

		private array $replaceArray = [];
		private string $templateMainFullPath = '';
		private string $templateContentFullPath = '';

		public function __construct(
			array $replaceArray,
			bool $withButton = false,
			string $contentTemplateKey = ''
		) {
			self::setTemplateMainSrcPath('');
			self::setTemplateMainNoButtonKey('');
			self::setTemplateMainWithButtonKey('');
			self::setTemplateSrcPath('');

			$mainTemplateKey = $withButton ? self::getTemplateMainWithButtonKey() : self::getTemplateMainNoButtonKey();

			$this->setReplaceArray($replaceArray);
			$this->setTemplateMainFullPath($mainTemplateKey);
			if (!Helper::IsNullOrEmpty($contentTemplateKey)) {
				$this->setTemplateContentFullPath($contentTemplateKey);
			}
		}

		private static function getTemplateSrcPath(): string {
			return self::$templateSrcPath;
		}

		private static function setTemplateSrcPath(string $path): void {
			if (Helper::IsNullOrEmpty($path)) {
				$path = self::getRootPath();
			}
			self::$templateSrcPath = $path;
		}

		private static function getTemplateMainSrcPath(): string {
			return self::$templateMainSrcPath;
		}

		private static function setTemplateMainSrcPath(string $path): void {
			if (Helper::IsNullOrEmpty($path)) {
				$path = self::getRootPath('Main/');
			}
			self::$templateMainSrcPath = $path;
		}

		private static function getTemplateMainNoButtonKey(): string {
			return self::$templateMainNoButtonKey;
		}

		private static function setTemplateMainNoButtonKey(string $key): void {
			if (Helper::IsNullOrEmpty($key)) {
				$key = self::MAIN_TEMPLATE_BOXED_DEFAULT_KEY;
			}

			self::$templateMainNoButtonKey = $key;
		}

		private static function getTemplateMainWithButtonKey(): string {
			return self::$templateMainWithButtonKey;
		}

		private static function setTemplateMainWithButtonKey(string $key): void {
			if (Helper::IsNullOrEmpty($key)) {
				$key = self::MAIN_TEMPLATE_BOXED_WITH_BUTTON_DEFAULT_KEY;
			}

			self::$templateMainWithButtonKey = $key;
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

		public function setMainTemplateContent(string $value): void {
			$this->appendToReplaceArray('email_content', $value);
		}

		private function getTemplateMainFullPath(): string {
			return $this->templateMainFullPath;
		}

		private function setTemplateMainFullPath(string $templateKey): void {
			$this->templateMainFullPath = Helper::RemoveMultipleSlashesInUrl(self::getTemplateMainSrcPath() . '/' . $templateKey . '.html');
		}

		private function getTemplateContentFullPath(): string {
			return $this->templateContentFullPath;
		}

		private function setTemplateContentFullPath(string $templateKey): void {
			$this->templateContentFullPath = Helper::RemoveMultipleSlashesInUrl(self::getTemplateSrcPath() . '/' . $templateKey . '.html');
		}

		public static function getDefaultReplaceArray(): array {
			return [
				'tenant_name' => Tenant::getName(),
				'tenant_year' => Tenant::getYear(),
				'tenant_logo' => Tenant::getLogo(),
				'tenant_primary_color' => Tenant::getPrimaryColor(),
				'tenant_secondary_color' => Tenant::getSecondaryColor(),
			];
		}

		public function getContent(): string {
			$this->fixReplaceArray();

			if (!Helper::IsNullOrEmpty($this->getTemplateContentFullPath())) {
				$this->appendToReplaceArray(
					'{{email_content}}',
					Helper::getContentFromFile($this->getTemplateContentFullPath(), $this->replaceArray)
				);
			}

			return Helper::getContentFromFile($this->getTemplateMainFullPath(), $this->replaceArray);
		}

		private function fixReplaceArray(): void {
			$this->replaceArray = array_merge($this->replaceArray, self::getDefaultReplaceArray());

			$newReplaceArray = [];
			foreach ($this->replaceArray as $key => $value) {
				$newReplaceArray['{{' . $key . '}}'] = $value;
			}
			$this->setReplaceArray($newReplaceArray);
		}

		private static function getRootPath(string $post = ''): string {
			$path = '';

			$dir = __DIR__;
			$prevDir = '';
			while (Helper::IsNullOrEmpty($path)) {
				$folders = Helper::GetAllFolders($dir);
				$files = Helper::GetAllFiles($dir);

				if (in_array($dir . '/src', $folders) && in_array($dir . '/README.md', $files)) {
					$path = $dir;
				} else {
					$prevDir = $dir;
					$dir = dirname($dir);
				}

				if ($dir === $prevDir) {
					throw new ConfigurationNotFoundException();
				}
			}
			$path .= '/src/Microservices/Notification/Templates/Email/' . $post;

			return $path;
		}



		// public static function getTemplateMainFullPathByTemplateKey(string $templateKey): string {
		// 	return Helper::RemoveMultipleSlashesInUrl(self::getTemplateMainSrcPath() . '/' . $templateKey . '.html');
		// }

		// private function getHtmlFromContentTemplate(): string {
		// 	return Helper::getContentFromFile($this->getTemplateContentFullPath(), $this->replaceArray);
		// }

		// private function getHtmlFromMainTemplate(): string {
		// 	return Helper::getContentFromFile($this->getTemplateMainFullPath(), $this->replaceArray);
		// }

		// private function getFullEmailHtml(): string {
		// 	$this->fixReplaceArray();

		// 	$html = $this->getHtmlFromContentTemplate();
		// 	$html = str_replace('{{email_content}}', $html, $this->getHtmlFromMainTemplate());

		// 	return $html;
		// }

		// public function getFullEmailHtmlBoxedWithButton(): string {
		// 	$this->setTemplateMainFullPath(self::getTemplateMainWithButtonKey());

		// 	return $this->getFullEmailHtml();
		// }

		// public function getFullEmailHtmlBoxed(): string {
		// 	$this->setTemplateMainFullPath(self::getTemplateMainNoButtonKey());

		// 	return $this->getFullEmailHtml();
		// }
	}
