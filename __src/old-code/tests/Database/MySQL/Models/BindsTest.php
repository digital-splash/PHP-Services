<?php
	namespace OldCode\DigitalSplash\Tests\Database\MySQL\Models;

	use OldCode\DigitalSplash\Database\MySQL\Models\Binds;
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

			$binds->appendToBinds(':name_2','Jane', PDO::PARAM_STR);
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

			$binds->appendToBinds(':name_3','Jack');
			$this->assertEqualsCanonicalizing([
				':name_1' => [
					'value' => 'John',
					'type' => PDO::PARAM_STR
				],
				':name_2' => [
					'value' => 'Jane',
					'type' => PDO::PARAM_STR
				],
				':name_3' => [
					'value' => 'Jack',
					'type' => PDO::PARAM_STR
				]
			], $binds->getBinds());

			$binds->appendArrayToBinds([
				':name_4' => [
					'value' => 'Jill',
					'type' => PDO::PARAM_STR
				],
				':age_1' => [
					'value' => 22
				],
				':age_2' => [
					'value' => 23,
					'type' => PDO::PARAM_INT
				],
			]);
			$this->assertEqualsCanonicalizing([
				':name_1' => [
					'value' => 'John',
					'type' => PDO::PARAM_STR
				],
				':name_2' => [
					'value' => 'Jane',
					'type' => PDO::PARAM_STR
				],
				':name_3' => [
					'value' => 'Jack',
					'type' => PDO::PARAM_STR
				],
				':name_4' => [
					'value' => 'Jill',
					'type' => PDO::PARAM_STR
				],
				':age_1' => [
					'value' => 22,
					'type' => PDO::PARAM_INT
				],
				':age_2' => [
					'value' => 23,
					'type' => PDO::PARAM_INT
				],
			], $binds->getBinds());

			$binds->clearBinds();
			$this->assertEqualsCanonicalizing([], $binds->getBinds());
		}
	}
