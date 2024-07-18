<?php
	namespace DigitalSplash\Tests\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Helpers\TypeHelper;

	final class TypeHelperTest extends TestCase {

		/**
		 * @dataProvider getClassPropertyTypeSuccessProvider
		 */
		public function testgetClassPropertyTypeSuccess(
			string $className,
			string $propertyName,
			?string $expected
		): void {
			$propType = TypeHelper::getClassPropertyType($className, $propertyName);
			$this->assertEquals($expected, $propType);
		}

		public function getClassPropertyTypeSuccessProvider(): array {
			return [
				'not_a_class' => [
					'className' => 'test',
					'propertyName' => 'int',
					'expected' => null
				],
				'class_does_not_have_property' => [
					'className' => TypeHelperTestClass1::class,
					'propertyName' => 'test',
					'expected' => null
				],
				'success_type_int' => [
					'className' => TypeHelperTestClass1::class,
					'propertyName' => 'int',
					'expected' => TypeHelper::TYPE_INT
				],
				'success_type_float' => [
					'className' => TypeHelperTestClass1::class,
					'propertyName' => 'float',
					'expected' => TypeHelper::TYPE_FLOAT
				],
				'success_type_string' => [
					'className' => TypeHelperTestClass1::class,
					'propertyName' => 'string',
					'expected' => TypeHelper::TYPE_STRING
				],
				'success_type_bool' => [
					'className' => TypeHelperTestClass1::class,
					'propertyName' => 'bool',
					'expected' => TypeHelper::TYPE_BOOL
				],
				'success_type_array' => [
					'className' => TypeHelperTestClass1::class,
					'propertyName' => 'array',
					'expected' => TypeHelper::TYPE_ARRAY
				],
				'success_type_object' => [
					'className' => TypeHelperTestClass1::class,
					'propertyName' => 'object',
					'expected' => TypeHelper::TYPE_OBJECT
				],
				'success_type_class_1' => [
					'className' => TypeHelperTestClass1::class,
					'propertyName' => 'obj2',
					'expected' => TypeHelperTestClass2::class
				],
				'success_type_class_2' => [
					'className' => TypeHelperTestClass2::class,
					'propertyName' => 'obj3',
					'expected' => TypeHelperTestClass3::class
				],
			];
		}

		/**
		 * @dataProvider isOfTypeSuccessProvider
		 */
		public function testIsOfTypeSuccess(
			$value,
			$type,
			bool $expected
		): void {
			$isOfType = TypeHelper::isOfType($value, $type);
			$this->assertEquals($expected, $isOfType);
		}

		public function isOfTypeSuccessProvider(): array {
			$valueBool = true;
			$valueInt = 1;
			$valueFloat = 1.01;
			$valueDouble = 1.0100;
			$valueNumeric1 = '1';
			$valueNumeric2 = '1.01';
			$valueNumeric3 = '1.0100';
			$valueString = 'This is a String';
			$valueArray = [
				'first_name' => 'John',
				'last_name' => 'Doe',
			];
			$valueObject = json_decode(json_encode($valueArray));
			$valueClass1 = new TypeHelperTestClass1();
			$valueClass2 = $valueClass1->obj2;
			$valueClass3 = $valueClass2->obj3;

			$valueClass1String = TypeHelper::getClassPropertyType($valueClass1::class, 'string');
			$valueClass1Obj2 = TypeHelper::getClassPropertyType($valueClass1::class, 'obj2');

			$combinations = [
				'invalid' => [
					'success' => [],
					'fail' => [
						$valueBool,
						$valueInt,
						$valueFloat,
						$valueDouble,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
						$valueString,
						$valueArray,
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3
					]
				],
				TypeHelper::TYPE_INT => [
					'success' => [
						$valueInt,
					],
					'fail' => [
						$valueBool,
						$valueFloat,
						$valueDouble,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
						$valueString,
						$valueArray,
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3,
					]
				],
				TypeHelper::TYPE_FLOAT => [
					'success' => [
						$valueFloat,
						$valueDouble
					],
					'fail' => [
						$valueBool,
						$valueInt,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
						$valueString,
						$valueArray,
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3
					]
				],
				TypeHelper::TYPE_DOUBLE => [
					'success' => [
						$valueFloat,
						$valueDouble
					],
					'fail' => [
						$valueBool,
						$valueInt,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
						$valueString,
						$valueArray,
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3
					]
				],
				TypeHelper::TYPE_NUMERIC => [
					'success' => [
						$valueInt,
						$valueFloat,
						$valueDouble,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
					],
					'fail' => [
						$valueBool,
						$valueString,
						$valueArray,
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3
					]
				],
				TypeHelper::TYPE_STRING => [
					'success' => [
						$valueString,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
					],
					'fail' => [
						$valueBool,
						$valueInt,
						$valueFloat,
						$valueDouble,
						$valueArray,
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3
					]
				],
				TypeHelper::TYPE_BOOL => [
					'success' => [
						$valueBool,
					],
					'fail' => [
						$valueInt,
						$valueFloat,
						$valueDouble,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
						$valueString,
						$valueArray,
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3
					]
				],
				TypeHelper::TYPE_ARRAY => [
					'success' => [
						$valueArray,
					],
					'fail' => [
						$valueBool,
						$valueInt,
						$valueFloat,
						$valueDouble,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
						$valueString,
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3
					]
				],
				TypeHelper::TYPE_OBJECT => [
					'success' => [
						$valueObject,
						$valueClass1,
						$valueClass2,
						$valueClass3,
					],
					'fail' => [
						$valueBool,
						$valueInt,
						$valueFloat,
						$valueDouble,
						$valueNumeric1,
						$valueNumeric2,
						$valueNumeric3,
						$valueString,
						$valueArray,
					]
				],
			];

			$providerArr = [];
			foreach ($combinations as $type => $typeArr) {
				foreach ($typeArr as $status => $values) {
					$expected = $status === 'success';

					foreach ($values as $i => $value) {
						$k = "type_{$type}_{$status}_{$i}";

						$providerArr[$k] = [
							'value' => $value,
							'type' => $type,
							'expected' => $expected
						];
					}
				}
			}

			$providerArr['reflection_type_built_in_as_string_success'] = [
				'value' => $valueString,
				'type' => $valueClass1String,
				'expected' => true
			];
			$providerArr['reflection_type_built_in_as_string_fail'] = [
				'value' => $valueArray,
				'type' => $valueClass1String,
				'expected' => false
			];
			$providerArr['reflection_type_not_built_in_as_obj2_success'] = [
				'value' => $valueClass2,
				'type' => $valueClass1Obj2,
				'expected' => true
			];
			$providerArr['reflection_type_not_built_in_as_obj2_fail'] = [
				'value' => $valueClass3,
				'type' => $valueClass1Obj2,
				'expected' => false
			];

			return $providerArr;
		}

	}

	class TypeHelperTestClass1 {
		public int $int;
		public float $float;
		public string $string;
		public bool $bool;
		public array $array;
		public object $object;
		public TypeHelperTestClass2 $obj2;
		public TypeHelperTestClass3 $obj3;

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
			$this->obj2 = new TypeHelperTestClass2();
			$this->obj3 = new TypeHelperTestClass3();
		}
	}

	class TypeHelperTestClass2 {
		public int $int;
		public float $float;
		public string $string;
		public bool $bool;
		public array $array;
		public TypeHelperTestClass3 $obj3;

		public function __construct() {
			$this->int = 1;
			$this->float = 1.01;
			$this->string = 'This is a string';
			$this->bool = true;
			$this->array = [
				'first_name' => 'John',
				'last_name' => 'Doe',
			];
			$this->obj3 = new TypeHelperTestClass3();
		}
	}

	class TypeHelperTestClass3 {
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
