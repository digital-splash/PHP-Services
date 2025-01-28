<?php

	namespace DigitalSplash\Tests\Exceptions;

	use DigitalSplash\Exceptions\ClassPropertyNotFoundException;
	use PHPUnit\Framework\TestCase;

	final class ClassPropertyNotFoundExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new ClassPropertyNotFoundException('{{Property}}', '{{Class}}');
			} catch (ClassPropertyNotFoundException $e) {
				$this->assertNotEquals('exception.main.classPropertyNotFound', $e->getMessage());
				$this->assertStringContainsString('{{Property}}', $e->getMessage());
				$this->assertStringContainsString('{{Class}}', $e->getMessage());
			}
		}
	}
