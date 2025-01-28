<?php

	namespace DigitalSplash\Tests\Exceptions\Base;

	use DigitalSplash\Exceptions\Base\BaseException;
	use PHPUnit\Framework\TestCase;

	final class BaseExceptionTest extends TestCase {
		public function testExceptionThrown(): void {
			$this->expectException(BaseException::class);
			$this->expectExceptionMessage('This is a test Message');

			throw new BaseException('This is a test Message');
		}

		public function testExceptionGetResponseCode(): void {
			try {
				throw new BaseException('::message::', [
					'::message::' => 'This is a test Message',
				], 100, 250);
			} catch (BaseException $e) {
				$this->assertEquals('This is a test Message', $e->getMessage());
				$this->assertEquals(100, $e->getCode());
				$this->assertEquals(250, $e->getSubCode());
			}
		}
	}
