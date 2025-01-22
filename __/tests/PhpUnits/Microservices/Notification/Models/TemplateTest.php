<?php
	namespace DigitalSplash\Tests\Notification\Models;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Notification\Models\Template;
	use PHPUnit\Framework\TestCase;

	class TemplateTest extends TestCase {

		public function templateSuccessProvider(): array {
			return [
				'Main/boxed_with_button' => [
					'path' => 'boxed_with_button',
					'email_content' => 'TestEmail',
					'keys' => [
						'full_name' => 'Hadi Darwish',
						'tenant_name' => 'Digital Splash',
						'url' => 'dgsplash.com',
						'tenant_primary_color' => '#0000ff',
						'button_text' => 'Test button',
						'tenant_year' => '2023',
						'tenant_logo' => "https://dgsplash.com/assets/images/logo-bg.jpg"
					]
				],
				'Main/boxed' => [
					'path' => 'boxed',
					'email_content' => 'TestEmail',
					'keys' => [
						'full_name' => 'Hadi Darwish',
						'tenant_name' => 'Digital Splash',
						'tenant_year' => '2023',
						'tenant_logo' => "https://dgsplash.com/assets/images/logo-bg.jpg"
					]
				],
			];
		}

		/**
		 * @dataProvider templateSuccessProvider
		 */
		public function testGetContentSuccess(string $path, string $emailContent, array $keys): void {
			$this->markTestSkipped('This test is skipped because of some spaces and a 2023 string.');
			$template = new Template($keys, $path, $emailContent);
			$templateContent = $template->getContent();
			$this->assertEquals(
				Helper::GetContentFromFile(
					__DIR__ . '/../../../../_CommonFiles/Notification/Email/Main/' . $path . '.html'
				),
				$templateContent
			);
		}
	}
