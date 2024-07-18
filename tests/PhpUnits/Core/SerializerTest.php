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

	}

	class SerializerTestClass1 extends BaseObject {
		private int $int;
		private float $float;
		private string $string;
		private bool $bool;
		private array $array;
		private object $object;
		private SerializerTestClass2 $obj2;

		public function __construct(
			$int = 1,
			$float = 1.01,
			$string = 'This is a string',
			$bool = true,
			$array = [
				'first_name' => 'John',
				'last_name' => 'Doe',
			]
		) {
			$this->int = $int;
			$this->float = $float;
			$this->string = $string;
			$this->bool = $bool;
			$this->array = $array;
			$this->object = json_decode(json_encode($this->array));
			$this->obj2 = new SerializerTestClass2();
		}

		/**
		 * @param array $arr
		 * @return static
		 */
		public static function arrayDeserialize(array $arr): self {
			return new self(
				$arr['int'] ?? 1,
				$arr['float'] ?? 1.01,
				$arr['string'] ?? 'This is a string',
				$arr['bool'] ?? true,
				$arr['array'] ?? [
					'first_name' => 'John',
					'last_name' => 'Doe',
				],
				$arr['object'] ?? null,
				$arr['obj2'] ?? new SerializerTestClass2()
			);
		}

		public function toArray(): array {
			return [
				'int' => $this->int,
				'float' => $this->float,
				'string' => $this->string,
				'bool' => $this->bool,
				'array' => $this->array,
				'object' => $this->object,
				'obj2' => $this->obj2,
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

		public function __construct(
			$int = 1,
			$float = 1.01,
			$string = 'This is a string',
			$bool = true,
			$array = [
				'first_name' => 'John',
				'last_name' => 'Doe',
			]
		) {
			$this->int = $int;
			$this->float = $float;
			$this->string = $string;
			$this->bool = $bool;
			$this->array = $array;
			$this->object = json_decode(json_encode($this->array));
		}

		/**
		 * @param array $arr
		 * @return static
		 */
		public static function arrayDeserialize(array $arr): self {
			return new self(
				$arr['int'] ?? 1,
				$arr['float'] ?? 1.01,
				$arr['string'] ?? 'This is a string',
				$arr['bool'] ?? true,
				$arr['array'] ?? [
					'first_name' => 'John',
					'last_name' => 'Doe',
				]
			);
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
