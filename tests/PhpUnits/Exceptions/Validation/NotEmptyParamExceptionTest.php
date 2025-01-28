<?php

	namespace DigitalSplash\Tests\Exceptions\Validation;

	use DigitalSplash\Exceptions\Validation\NotEmptyParamException;
	use PHPUnit\Framework\TestCase;

	final class NotEmptyParamExceptionTest extends TestCase {
		public function testExceptionMessage(): void {
			try {
				throw new NotEmptyParamException('{{Params goes here...}}');
			} catch (NotEmptyParamException $e) {
				$this->assertNotEquals('exception.validation.notEmptyParam', $e->getMessage());
				$this->assertStringContainsString('{{Params goes here...}}', $e->getMessage());
			}
		}
	}
