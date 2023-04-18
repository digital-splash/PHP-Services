<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models\Base;

	use DigitalSplash\Database\MySQL\Models\Base\SingleValue;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
	use PHPUnit\Framework\TestCase;

	class SingleValueTest extends TestCase {
			
			public function testGetFinalString(): void {
				$singleValue = new SingleValue('SET');
				$this->assertEquals('', $singleValue->getFinalString());
			}
	
			public function testSetFinalString(): void {
				$singleValue = new SingleValue('SET');
				$singleValue->setFinalString('test');
				$this->assertEquals('test', $singleValue->getFinalString());
			}
	
			public function testClearFinalString(): void {
				$singleValue = new SingleValue('SET');
				$singleValue->setFinalString('test');
				$singleValue->clearFinalString();
				$this->assertEquals('', $singleValue->getFinalString());
			}

			public function testGetValue(): void {
				$singleValue = new SingleValue('SET');
				$this->assertEquals(null, $singleValue->getValue());
			}

			public function testSetValue(): void {
				$singleValue = new SingleValue('SET');
				$singleValue->setValue('test');
				$this->assertEquals('test', $singleValue->getValue());
			}

			public function testClearValue(): void {
				$singleValue = new SingleValue('SET');
				$singleValue->setValue('test');
				$singleValue->clearValue();
				$this->assertEquals(null, $singleValue->getValue());
			}
	}