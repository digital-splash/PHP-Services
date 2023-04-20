<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Binds;
	use PDO;
	use PHPUnit\Framework\TestCase;

	class BindsTest extends TestCase {

		public function testBindsAllCases(): void {
			$binds = new Binds();
			$this->assertEqualsCanonicalizing([], $binds->getBinds());

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

			$binds->appendToBinds(':name_2', [
				'value' => 'Jane',
				'type' => PDO::PARAM_STR
			]);
			$this->assertEqualsCanonicalizing([
				':name_1' => [
					'value' => 'John',
					'type' => PDO::PARAM_STR
				],
				':name_2' => [
					'value' => 'Jane',
					'type' => PDO::PARAM_STR
				]
			], $binds->getBinds());

			$binds->clearBinds();
			$this->assertEqualsCanonicalizing([], $binds->getBinds());
		}
	}
