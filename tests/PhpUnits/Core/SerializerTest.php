<?php

	namespace DigitalSplash\Tests\Core;

	use DigitalSplash\Core\Serializer;
	use PHPUnit\Framework\TestCase;

	class SerializerTest extends TestCase {
		public function testToArraySuccess(): void {
			$obj = new SerializerTestClass1();
			$arr = $obj->toArray();

			$this->assertEquals($obj->int, $arr['int']);

			$this->assertEqualsCanonicalizing($obj->obj2->toArray(), $arr['obj2']);
			$this->assertEquals(($obj->obj2)->string, $arr['obj2']['string']);
		}

		public function testJsonSerializeSuccess(): void {
			$obj = new SerializerTestClass1();

			$toArrJson = json_encode($obj->toArray());
			$objectJson = json_encode($obj);
			$this->assertEqualsCanonicalizing($toArrJson, $objectJson);

			$toArrJson2 = json_encode($obj->obj2->toArray());
			$objectJson2 = json_encode($obj->obj2);
			$this->assertEqualsCanonicalizing($toArrJson2, $objectJson2);
		}

		public function testArrayDeserializeOneArgumentAsArraySuccess(): void {
			$arr = [
				'int' => 125,
				'string' => 'This is the Array Deserialize Test',
				'bool' => false,
			];

			$obj = SerializerTestClass1::arrayDeserialize([$arr]);

			$this->assertInstanceOf(SerializerTestClass1::class, $obj);
			foreach ($arr as $k => $v) {
				$this->assertEquals($v, $obj->$k);
			}
			$this->assertEquals(1.01, $obj->float);
		}

		public function testArrayDeserializeMultipleArgumentsSuccess(): void {
			$arr = [
				'array' => [],
				'int' => 125,
				'bool' => false,
				'string' => 'This is the Array Deserialize Test',
			];

			$obj = SerializerTestClass3::arrayDeserialize($arr);

			$this->assertInstanceOf(SerializerTestClass3::class, $obj);
			foreach ($arr as $k => $v) {
				$this->assertEquals($v, $obj->$k);
			}
		}

		public function testDeserializeMultiple(): void {
			$arr = [
				[
					'int' => 125,
					'string' => 'This is the Array Deserialize Test 1',
					'bool' => false,
				],
				[
					'int' => 265,
					'string' => 'This is the Array Deserialize Test 2',
				],
			];

			$objects = SerializerTestClass1::deserializeMultiple([$arr]);

			foreach ($objects as $k => $object) {
				$this->assertInstanceOf(SerializerTestClass1::class, $object);

				$this->assertEquals(1.01, $object->float);
				foreach ($arr[$k] as $k2 => $v2) {
					$this->assertEquals($v2, $object->$k2);
				}

			}

		}
	}

	class SerializerTestClass1 extends Serializer {
		public int $int;
		public float $float;
		public string $string;
		public bool $bool;
		public array $array;
		public object $object;
		public SerializerTestClass2 $obj2;

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

	class SerializerTestClass2 extends Serializer {
		public int $int;
		public float $float;
		public string $string;
		public bool $bool;
		public array $array;
		public object $object;

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

	class SerializerTestClass3 extends Serializer {
		public ?int $int;
		public ?float $float;
		public ?string $string;
		public ?bool $bool;
		public ?array $array;

		public function __construct(
			?int    $int = null,
			?float  $float = null,
			?string $string = null,
			?bool   $bool = null,
			?array  $array = null
		) {
			$this->int = $int;
			$this->float = $float;
			$this->string = $string;
			$this->bool = $bool;
			$this->array = $array;
		}

		public function toArray(): array {
			return [
				'int' => $this->int,
				'float' => $this->float,
				'string' => $this->string,
				'bool' => $this->bool,
				'array' => $this->array,
			];
		}
	}
