<?php
	namespace DigitalSplash\Tests\Core;

	use DigitalSplash\Core\BaseObject;
	use DigitalSplash\Exceptions\ClassPropertyNotFoundException;
	use DigitalSplash\Exceptions\InvalidTypeException;
	use PHPUnit\Framework\TestCase;

	class BaseObjectTest extends TestCase {

		public function testGetPropertyNotFoundThrows(): void {
			$this->expectException(ClassPropertyNotFoundException::class);

			$obj = new TestClass1();
			$obj->get('invalid');
		}

		public function testGetSuccess(): void {
			$obj = new TestClass1();

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

			$obj = new TestClass1();
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

		public function testSetSuccess(): void {
			$obj = new TestClass1();

			$this->assertEquals(1, $obj->get('int'));
			$this->assertEquals(1.01, $obj->get('float'));
			$this->assertEquals('This is a string', $obj->get('string'));
			$this->assertEquals(true, $obj->get('bool'));
			$this->assertEqualsCanonicalizing(new TestClass2(), $obj->get('obj2'));
		}

		// public function testSetSuccess(): void {
		// 	$dto = new TestDTO(1, 'name', [], new TestClass1(1, 'name'));

		// 	$dto->set('id', 2);
		// 	$dto->set('name', 'name2');
		// 	$dto->set('testDTOs', [
		// 		new TestDTO1(1, 'name'),
		// 		new TestDTO1(2, 'name2'),]);
		// 	$dto->set('testClass1', new TestClass1(2, 'name2'));

		// 	$this->assertEquals(2, $dto->get('id'));
		// 	$this->assertEquals('name2', $dto->get('name'));
		// 	$this->assertEquals( 1, $dto->get('testDTOs')[0]->get('id'));
		// 	$this->assertEquals(new TestClass1(2, 'name2'), $dto->get('testClass1'));
		// }

	}

	class TestClass1 extends BaseObject {
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
	}
