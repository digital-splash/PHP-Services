<?php
	namespace DigitalSplash\Tests\Core;

	use DigitalSplash\Core\BaseObject;
	use DigitalSplash\Exceptions\ClassPropertyNotFoundException;
	use DigitalSplash\Exceptions\InvalidTypeException;
	use PHPUnit\Framework\TestCase;

	class BaseObjectTest extends TestCase {

		public function testGetPropertyNotFoundThrows(): void {
			$this->expectException(ClassPropertyNotFoundException::class);

			$obj = new BaseObjectTestClass();
			$obj->get('invalid');
		}

		public function testGetSuccess(): void {
			$obj = new BaseObjectTestClass();

			$this->assertEquals(1, $obj->get('int'));
			$this->assertEquals(1.01, $obj->get('float'));
			$this->assertEquals('This is a string', $obj->get('string'));
			$this->assertEquals(true, $obj->get('bool'));
			$this->assertEqualsCanonicalizing(new TestClass2(), $obj->get('obj2'));
		}

		/**
		 * @dataProvider setThrowsProvider
		 */
		public function testSetThrows(
			string $exception,
			string $propertyName,
			$value
		): void {
			$this->expectException($exception);

			$obj = new BaseObjectTestClass();
			$obj->set($propertyName, $value);
		}

		public function setThrowsProvider(): array {
			return [
				'class_property_not_found' => [
					'exception' => ClassPropertyNotFoundException::class,
					'propertyName' => 'invalid',
					'value' => 'New Value'
				],
				'invalid_type_01' => [
					'exception' => InvalidTypeException::class,
					'propertyName' => 'int',
					'value' => 'New Value'
				],
				'invalid_type_02' => [
					'exception' => InvalidTypeException::class,
					'propertyName' => 'string',
					'value' => 1
				],
				'invalid_type_03' => [
					'exception' => InvalidTypeException::class,
					'propertyName' => 'obj2',
					'value' => []
				],
			];
		}

		/**
		 * @dataProvider setSuccessProvider
		 */
		public function testSetSuccess(
			string $param,
			$oldValue,
			$newValue
		): void {
			$obj = new BaseObjectTestClass();

			$this->assertEquals($oldValue, $obj->get($param));
			$obj->set($param, $newValue);
			$this->assertEquals($newValue, $obj->get($param));
		}

		public function setSuccessProvider(): array {
			return [
				'int' => [
					'param' => 'int',
					'oldValue' => 1,
					'newValue' => 2,
				],
				'float' => [
					'param' => 'float',
					'oldValue' => 1.01,
					'newValue' => 23.15,
				],
				'string' => [
					'param' => 'string',
					'oldValue' => 'This is a string',
					'newValue' => 'This is a new string',
				],
				'bool' => [
					'param' => 'bool',
					'oldValue' => true,
					'newValue' => false,
				],
				'obj2' => [
					'param' => 'obj2',
					'oldValue' => new TestClass2(),
					'newValue' => new TestClass2(),
				],
			];
		}
	}

	class BaseObjectTestClass extends BaseObject {
		private int $int;
		private float $float;
		private string $string;
		private bool $bool;
		private array $array;
		private object $object;
		private TestClass2 $obj2;

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

		public function toArray(): array {
			return [];
		}
	}

	class TestClass2 extends BaseObject {
		private int $int;
		private float $float;
		private string $string;
		private bool $bool;
		private array $array;

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

		public function toArray(): array {
			return [];
		}
	}
