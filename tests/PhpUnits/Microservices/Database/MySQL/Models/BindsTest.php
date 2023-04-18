<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Binds;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
	use PDO;
	use PHPUnit\Framework\TestCase;

	class BindsTest extends TestCase {
				
		public function testGetBinds(): void {
			$binds = new Binds();
			$this->assertEqualsCanonicalizing([], $binds->getBinds());
		}

		public function testSetBinds(): void {
			$binds = new Binds();
			$binds->setBinds([
				':name_1' => [
					'value' => 'John',
					'type' => PDO::PARAM_STR
				]
			]);
			$this->assertEqualsCanonicalizing([
				':name_1' => [
					'value' => 'John',
					'type' => PDO::PARAM_STR
				]
			], $binds->getBinds());
		}

		public function testClearBinds(): void {
			$binds = new Binds();
			$binds->setBinds([
				':name_1' => [
					'value' => 'John',
					'type' => PDO::PARAM_STR
				]
			]);
			$binds->clearBinds();
			$this->assertEqualsCanonicalizing([], $binds->getBinds());
		}

		public function testAppendToBinds(): void {
			$binds = new Binds();
			$binds->appendToBinds(':name_1', [
				'value' => 'John',
				'type' => PDO::PARAM_STR
			]);
			$this->assertEqualsCanonicalizing([
				':name_1' => [
					'value' => 'John',
					'type' => PDO::PARAM_STR
				]
			], $binds->getBinds());
		}
	}