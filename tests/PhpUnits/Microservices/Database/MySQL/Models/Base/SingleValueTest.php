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
	}