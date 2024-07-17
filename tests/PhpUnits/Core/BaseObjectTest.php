<?php
	namespace DigitalSplash\Tests\Core;

	use DigitalSplash\Core\BaseObject;
	use DigitalSplash\Exceptions\ClassPropertyNotFound;
	use PHPUnit\Framework\TestCase;

	class BaseObjectTest extends TestCase {

		public function testGetPropertyNotFoundThrows(): void {
			$this->expectException(ClassPropertyNotFound::class);
			$this->expectExceptionMessage('Property \"invalid\" not found in class \"' . TestClass1::class . '\".');

			$obj = new TestClass1();
			$obj->get('invalid');
		}

		// public function testGetSuccess(): void {
		// 	$obj = new TestClass1();

		// 	$this->assertEquals(1, $dto->get('id'));
		// 	$this->assertEquals('name', $dto->get('name'));
		// 	$this->assertEquals(null, $dto->get('hello'));
		// }


	}

	class TestClass1 extends BaseObject {
		public int $int;
		public float $float;
		public string $string;
		public bool $bool;
		public array $array;
		public object $object;
		public TestClass2 $obj2;

		public function __construct() {
			$this->int = 1;
			$this->float = 1.01;
			$this->string = 'This is a string';
			$this->bool = true;
			$this->array = [
				'first_name' => 'John',
				'last_name' => 'Doe',
			];
			$this->object = json_decode(json_encode($this->array));
			$this->obj2 = new TestClass2();
		}
	}

	class TestClass2 extends BaseObject {
		public int $int;
		public float $float;
		public string $string;
		public bool $bool;
		public array $array;

		public function __construct() {
			$this->int = 1;
			$this->float = 1.01;
			$this->string = 'This is a string';
			$this->bool = true;
			$this->array = [
				'first_name' => 'John',
				'last_name' => 'Doe',
			];
		}
	}
