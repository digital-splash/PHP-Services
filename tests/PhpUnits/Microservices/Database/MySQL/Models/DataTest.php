<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Data;
	use PHPUnit\Framework\TestCase;

	class DataTest extends TestCase {
				
		public function testGetData(): void {
			$data = new Data();
			$this->assertEqualsCanonicalizing([], $data->getData());
		}

		public function testSetData(): void {
			$data = new Data();
			$data->setData(['name' => 'John', 'age' => 25]);
			$this->assertEqualsCanonicalizing(['name' => 'John', 'age' => 25], $data->getData());
		}

		public function testClearData(): void {
			$data = new Data();
			$data->setData(['name' => 'John', 'age' => 25]);
			$data->clearData();
			$this->assertEqualsCanonicalizing([], $data->getData());
		}

		public function testAppendToData(): void {
			$data = new Data();
			$data->appendToData('name','John');
			$this->assertEqualsCanonicalizing(['name' => 'John'], $data->getData());
		}
	}