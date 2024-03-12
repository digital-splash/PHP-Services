<?php
	namespace OldCode\DigitalSplash\Tests\Database\MySQL\Models;

	use OldCode\DigitalSplash\Database\MySQL\Models\Data;
	use PHPUnit\Framework\TestCase;

	class DataTest extends TestCase {

		public function testDataAllCases(): void {
			$data = new Data();
			$this->assertEqualsCanonicalizing([], $data->getData());

			$data->setData([
				'name' => 'John',
				'age' => 25
			]);
			$this->assertEqualsCanonicalizing([
				'name' => 'John',
				'age' => 25
			], $data->getData());

			$data->appendToData('email','john@example.com');
			$this->assertEqualsCanonicalizing([
				'name' => 'John',
				'age' => 25,
				'email' => 'john@example.com'
			], $data->getData());
			$data->appendArrayToData([
			   'phone' => '1234567890',
				'address' => '123 Main St.'
			]);
			$this->assertEqualsCanonicalizing([
				'name' => 'John',
				'age' => 25,
				'email' => 'john@example.com',
				'phone' => '1234567890',
				'address' => '123 Main St.'
			], $data->getData());

			$data->clearData();
			$this->assertEqualsCanonicalizing([], $data->getData());
		}
	}
