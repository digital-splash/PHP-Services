<?php
	namespace DigitalSplash\Tests\Core;

	use DigitalSplash\Core\BaseObject;
	use PHPUnit\Framework\TestCase;

	class SerializerTest extends TestCase {

		public function testArrayDeserializeSuccess(): void {
			$arr = [
				'int' => 125,
				'string' => 'This is the Array Deserialize Test',
				'bool' => false
			];

			$obj = SerializerTestClass1::arrayDeserialize($arr);

			$this->assertInstanceOf(SerializerTestClass1::class, $obj);
			foreach ($arr as $k => $v) {
				$this->assertEquals($v, $obj->get($k));
			}
			$this->assertEquals(1.01, $obj->get('float'));
		}

		public function testToArraySuccess(): void {
			$obj = new SerializerTestClass1();
			$arr = $obj->toArray();

			$this->assertEquals($obj->get('int'), $arr['int']);

			$this->assertEqualsCanonicalizing(($obj->get('obj2'))->toArray(), $arr['obj2']);
			$this->assertEquals(($obj->get('obj2'))->get('string'), $arr['obj2']['string']);
		}

		public function testJsonSerializeSuccess(): void {
			$obj = new SerializerTestClass1();

			$toArrJson = json_encode($obj->toArray());
			$objectJson = json_encode($obj);
			$this->assertEqualsCanonicalizing($toArrJson, $objectJson);

			$toArrJson2 = json_encode($obj->get('obj2')->toArray());
			$objectJson2 = json_encode($obj->get('obj2'));
			$this->assertEqualsCanonicalizing($toArrJson2, $objectJson2);
		}

		public function testDeserializeMultiple(): void {
			$arr = [
				[
					'int' => 125,
					'string' => 'This is the Array Deserialize Test 1',
					'bool' => false
				],
				[
					'int' => 265,
					'string' => 'This is the Array Deserialize Test 2'
				]
			];

			$objects = SerializerTestClass1::deserializeMultiple($arr);

			foreach ($objects as $k => $object) {
				$this->assertInstanceOf(SerializerTestClass1::class, $object);

				$this->assertEquals(1.01, $object->get('float'));
				foreach ($arr[$k] as $k2 => $v2) {
					$this->assertEquals($v2, $object->get($k2));
				}

			}

		}

	}

	class SerializerTestClass1 extends BaseObject {
		private int $int;
		private float $float;
		private string $string;
		private bool $bool;
		private array $array;
		private object $object;
		private SerializerTestClass2 $obj2;

		public function __construct(?array $arr = null) {
			$this->int = $arr['int'] ?? 1;
			$this->float = $arr['float'] ?? 1.01;
			$this->string = $arr['string'] ?? 'This is a string';
			$this->bool = $arr['bool'] ?? true;
			$this->array = $arr['array'] ?? [
				'first_name' => 'John',
				'last_name' => 'Doe',
			];
			$this->object = json_decode(json_encode($this->array));
			$this->obj2 = new SerializerTestClass2();
		}

		public function toArray(): array {
			return [
				'int' => $this->int,
				'float' => $this->float,
				'string' => $this->string,
				'bool' => $this->bool,
				'array' => $this->array,
				'object' => $this->object,
				'obj2' => $this->obj2->toArray(),
			];
		}
	}

	class SerializerTestClass2 extends BaseObject {
		private int $int;
		private float $float;
		private string $string;
		private bool $bool;
		private array $array;
		private object $object;

		public function __construct(?array $arr = null) {
			$this->int = $arr['int'] ?? 1;
			$this->float = $arr['float'] ?? 1.01;
			$this->string = $arr['string'] ?? 'This is a string';
			$this->bool = $arr['bool'] ?? true;
			$this->array = $arr['array'] ?? [
				'first_name' => 'John',
				'last_name' => 'Doe',
			];
			$this->object = json_decode(json_encode($this->array));
		}

		public function toArray(): array {
			return [
				'int' => $this->int,
				'float' => $this->float,
				'string' => $this->string,
				'bool' => $this->bool,
				'array' => $this->array,
				'object' => $this->object,
			];
		}
	}
