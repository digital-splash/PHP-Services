<?php

	namespace DigitalSplash\Tests\Exceptions\Notification\Email;

	use DigitalSplash\Exceptions\Notification\Email\PhpMailerException;
	use PHPUnit\Framework\TestCase;

	final class PhpMailerExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new PhpMailerException('{{Params goes here...}}');
			} catch (PhpMailerException $e) {
				$this->assertNotEquals('exception.notification.email.phpMailer', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
