<?php
	namespace DigitalSplash\Tests\Notification\Models;

use DigitalSplash\Helpers\Helper;
use DigitalSplash\Notification\Models\Template;
	use PHPUnit\Framework\TestCase;

	class TemplateTest extends TestCase {

		public function templateSuccessProvider(): array {
			return [
				'Main/boxed_with_button' => [
					'path' => 'Main/boxed_with_button',
					'keys' => [
						'{{full_name}}' => 'Hadi Darwish',
						'{{email_content}}' => 'Email Content Test',
						'{{tenant_name}}' => 'Digital Splash',
						'{{url}}' => 'dgsplash.com',
						'{{tenant_main_color}}' => '#0000ff',
						'{{button_text}}' => 'Test button',
						'{{tenant_year}}' => '2023',
						'{{tenant_logo}}' => "__DIR__ . '../../../../_CommonFiles/Media/users/profile/user-01.jpg'"
					]
				],
				'Main/boxed' => [
					'path' => 'Main/boxed',
					'keys' => [
						'{{full_name}}' => 'Hadi Darwish',
						'{{email_content}}' => 'Email Content Test',
						'{{tenant_name}}' => 'Digital Splash',
						'{{tenant_year}}' => '2023',
						'{{tenant_logo}}' => "__DIR__ . '../../../../_CommonFiles/Media/users/profile/user-01.jpg'"
					]
				],
			];
		}

		/**
		 * @dataProvider templateSuccessProvider
		 */
		public function testGetContentSuccess(string $path, array $keys): void {
			$template = new Template($keys);
			$templatePath = __DIR__ . '../../../../src/Microservices/Notification/Templates/Email/' . $path . '.html';
			$templateContent = $template->getContent($templatePath, $keys);
			$this->assertStringEqualsFile(Helper::GetContentFromFile(__DIR__ . '../../../../_CommonFiles/Notification/Email/' . $path . '.html'), $templateContent);
		}
	}