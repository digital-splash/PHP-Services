<?php
	namespace DigitalSplash\Notification\Models;

	use DigitalSplash\Exceptions\Configuration\ConfigurationNotFoundException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Models\Tenant;

	class Template {
		const MAIN_TEMPLATE_BOXED_DEFAULT_KEY = 'boxed';
		const MAIN_TEMPLATE_BOXED_WITH_BUTTON_DEFAULT_KEY = 'boxed_with_button';

		// const TEMPLATE_PATH = __DIR__ . '/../Templates/Email/';
		private static $TEMPLATE_PATH;
		private static $TEMPLATE_MAIN_PATH;

		private $replaceArray = [];
		private $templatePath = '';
		private $templateContentPath = '';

		public function __construct(
			array $replaceArray,
			string $templatePath,
			string $templateContentPath = ''
		) {
			$this->replaceArray = $replaceArray;
			$this->templatePath = Helper::RemoveMultipleSlashesInUrl(self::$TEMPLATE_PATH . 'Main/' . $templatePath . '.html');
			if ($templateContentPath !== '') {
				$this->templateContentPath = Helper::RemoveMultipleSlashesInUrl(self::$TEMPLATE_PATH . $templateContentPath . '.html');
			}
		}

		public static function getTemplateSrcPath(): string {
			return self::$TEMPLATE_PATH;
		}

		public static function setTemplateSrcPath($path): void {
			if (Helper::IsNullOrEmpty($path)) {
				$dir = __DIR__;
				$prevDir = '';
				while (Helper::IsNullOrEmpty($path)) {
					$folders = Helper::GetAllFolders($dir);
					$files = Helper::GetAllFolders($dir);
					if (in_array('src', $folders) && in_array('README.md', $files)) {
						$path = $dir;
					} else {
						$prevDir = $dir;
						$dir = dirname($dir);
					}

					if ($dir === $prevDir) {
						throw new ConfigurationNotFoundException();
					}
				}

				$path .= '/src/Microservices/Notification/Templates/Email/';
			}

			self::$TEMPLATE_PATH = $path;
		}

		public static function getTemplateMainSrcPath(): string {
			return self::$TEMPLATE_MAIN_PATH;
		}

		public static function setTemplateMainSrcPath($path): void {
			if (Helper::IsNullOrEmpty($path)) {
				$dir = __DIR__;
				$prevDir = '';
				while (Helper::IsNullOrEmpty($path)) {
					$folders = Helper::GetAllFolders($dir);
					$files = Helper::GetAllFolders($dir);
					if (in_array('src', $folders) && in_array('README.md', $files)) {
						$path = $dir;
					} else {
						$prevDir = $dir;
						$dir = dirname($dir);
					}

					if ($dir === $prevDir) {
						throw new ConfigurationNotFoundException();
					}
				}

				$path .= '/src/Microservices/Notification/Templates/Email/Main/';
			}

			self::$TEMPLATE_MAIN_PATH = $path;
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
			$this->templatePath = Helper::RemoveMultipleSlashesInUrl(self::$TEMPLATE_PATH . $templatePath . '.html');;
		}

		public function getTemplateContentPath(): string {
			return $this->templateContentPath;
		}

		public function setTemplateContentPath(string $templateContentPath): void {
			$this->templateContentPath = Helper::RemoveMultipleSlashesInUrl(self::$TEMPLATE_PATH . $templateContentPath . '.html');
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

			if (!Helper::IsNullOrEmpty($this->templateContentPath)) {
				$this->appendToReplaceArray(
					'{{email_content}}',
					Helper::getContentFromFile($this->templateContentPath, $this->replaceArray)
				);
			}

			return Helper::getContentFromFile($this->templatePath, $this->replaceArray);
		}

		private function fixReplaceArray(): void {
			$this->replaceArray = array_merge($this->replaceArray, self::getDefaultReplaceArray());

			$newReplaceArray = [];
			foreach ($this->replaceArray as $key => $value) {
				$newReplaceArray['{{' . $key . '}}'] = $value;
			}
			$this->setReplaceArray($newReplaceArray);
		}
	}
