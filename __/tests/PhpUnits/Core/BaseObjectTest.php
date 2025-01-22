<?php
	namespace DigitalSplash\Tests\Core;

	use DigitalSplash\Core\BaseObject;
	use DigitalSplash\Core\Models\BaseObjectParamModel;
	use DigitalSplash\Core\Models\BaseObjectValidationTypeEnum;
	use DigitalSplash\Exceptions\ClassPropertyNotFoundException;
	use DigitalSplash\Exceptions\InvalidArgumentException;
	use DigitalSplash\Exceptions\InvalidParamException;
	use DigitalSplash\Exceptions\InvalidTypeException;
	use DigitalSplash\Exceptions\MissingParamsException;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\TypeHelper;
	use PHPUnit\Framework\TestCase;

	class BaseObjectTest extends TestCase {

		/**
		 * @dataProvider constructThrowsProvider
		 */
		public function testConstructThrows(
			string $exception,
			string $exceptionMessage,
			string $class,
			array $arr
		): void {
			$this->expectException($exception);
			$this->expectExceptionMessage($exceptionMessage);

			new $class($arr);
		}

		public function constructThrowsProvider(): array {
			return [
				'invalid_param' => [
					'exception' => InvalidParamException::class,
					'exceptionMessage' => 'The parameter(s) (test) is/are invalid',
					'class' => BaseObjectTestInvalidParamClass::class,
					'arr' => [],
				],
				'not_empty_param' => [
					'exception' => NotEmptyParamException::class,
					'exceptionMessage' => 'The parameter(s) (float) should not be Empty',
					'class' => BaseObjectTestNotEmptyParamClass::class,
					'arr' => [],
				],
				'invalid_type' => [
					'exception' => InvalidTypeException::class,
					'exceptionMessage' => 'Invalid type "string" for property "float". Expected type is "float".',
					'class' => BaseObjectTestClass1::class,
					'arr' => [
						'float' => 'This is a string'
					],
				],
				'invalid_type_2' => [
					'exception' => InvalidTypeException::class,
					'exceptionMessage' => 'Invalid type "string" for property "obj2". Expected type is "' . BaseObjectTestClass2::class . '".',
					'class' => BaseObjectTestClass1::class,
					'arr' => [
						'obj2' => 'This is a string'
					],
				],
			];
		}

		/**
		 * @dataProvider validateThrowsProvider
		 */
		public function testValidateThrows(
			string $exception,
			string $exceptionMessage,
			string $class,
			array $arr
		): void {
			$this->expectException($exception);
			$this->expectExceptionMessage($exceptionMessage);

			new $class($arr);
		}

		public function validateThrowsProvider(): array {
			return [
				'invalid_validation_rule' => [
					'exception' => InvalidArgumentException::class,
					'exceptionMessage' => 'Invalid argument "validationRule" having the value "TestValidationRule". Allowed value(s): "' . implode(', ', BaseObjectValidationTypeEnum::ALLOWED) . '"',
					'class' => BaseObjectTestInvalidValidationRuleClass::class,
					'arr' => []
				],
				'missing_one_param' => [
					'exception' => MissingParamsException::class,
					'exceptionMessage' => 'Missing Parameter(s): `float`.',
					'class' => BaseObjectTestNullableClass::class,
					'arr' => [
						'int' => 1,
						'string' => 'This is a string',
						'bool' => true,
						'array' => [
							'This is an array'
						],
						'object' => json_decode(json_encode([
							'key' => 'value'
						])),
					]
				],
				'missing_two_params' => [
					'exception' => MissingParamsException::class,
					'exceptionMessage' => 'Missing Parameter(s): `float`, `bool`.',
					'class' => BaseObjectTestNullableClass::class,
					'arr' => [
						'int' => 1,
						'string' => 'This is a string',
						'array' => [
							'This is an array'
						],
						'object' => null,
					]
				],
				'not_empty_one_param' => [
					'exception' => MissingParamsException::class,
					'exceptionMessage' => 'Missing Parameter(s): `int`.',
					'class' => BaseObjectTestNullableClass::class,
					'arr' => [
						'int' => 0,
						'float' => 0,
						'string' => 'This is a string',
						'bool' => true,
						'array' => [
							'This is an array'
						],
						'object' => null,
					]
				],
				'not_empty_two_param' => [
					'exception' => MissingParamsException::class,
					'exceptionMessage' => 'Missing Parameter(s): `int`, `string`.',
					'class' => BaseObjectTestNullableClass::class,
					'arr' => [
						'int' => 0,
						'float' => 0,
						'string' => '',
						'bool' => true,
						'array' => [
							'This is an array'
						],
						'object' => null,
					]
				],
				'not_empty_three_param' => [
					'exception' => MissingParamsException::class,
					'exceptionMessage' => 'Missing Parameter(s): `int`, `string`, `array`.',
					'class' => BaseObjectTestNullableClass::class,
					'arr' => [
						'int' => 0,
						'float' => 0,
						'string' => '',
						'bool' => true,
						'array' => [],
						'object' => null,
					],
				],
				'mix_and_match_1' => [
					'exception' => MissingParamsException::class,
					'exceptionMessage' => 'Missing Parameter(s): `int`, `string`, `array`, `bool`.',
					'class' => BaseObjectTestNullableClass::class,
					'arr' => [
						'int' => 0,
						'float' => 0,
						'string' => '',
						'bool' => null,
						'array' => [],
						'object' => null,
					]
				],
				'mix_and_match_2' => [
					'exception' => MissingParamsException::class,
					'exceptionMessage' => 'Missing Parameter(s): `int`, `string`, `array`, `float`, `bool`.',
					'class' => BaseObjectTestNullableClass::class,
					'arr' => [
						'int' => 0,
						'float' => null,
						'string' => '',
						'bool' => null,
						'array' => [],
						'object' => null,
					]
				],
				'mix_and_match_3' => [
					'exception' => MissingParamsException::class,
					'exceptionMessage' => 'Missing Parameter(s): `int`, `string`, `array`, `float`, `bool`.',
					'class' => BaseObjectTestNullableClass::class,
					'arr' => []
				],
			];
		}

		/**
		 * @dataProvider toArraySuccessProvider
		 */
		public function testToArraySuccess(
			string $class,
			array $arr,
			array $expected
		): void {
			/**
			 * @var BaseObject
			 */
			$obj = new $class($arr);
			$actual = $obj->toArray();

			$this->assertEqualsCanonicalizing($expected, $actual);
		}

		public function toArraySuccessProvider(): array {
			return [
				'test_class1' => [
					'class' => BaseObjectTestClass1::class,
					'arr' => [],
					'expected' => [
						'int' => 1,
						'float' => 1.01,
						'string' => 'This is a string',
						'bool' => true,
						'array' => [
							'first_name' => 'John',
							'last_name' => 'Doe',
						],
						'object' => json_decode(json_encode([
							'first_name' => 'John',
							'last_name' => 'Doe',
						])),
						'obj2' => [
							'int' => 1,
							'float' => 1.01,
							'string' => 'This is a string',
							'bool' => true,
							'array' => [
								'first_name' => 'John',
								'last_name' => 'Doe',
							],
						]
					]
				],
				'test_class2' => [
					'class' => BaseObjectTestClass2::class,
					'arr' => [],
					'expected' => [
						'int' => 1,
						'float' => 1.01,
						'string' => 'This is a string',
						'bool' => true,
						'array' => [
							'first_name' => 'John',
							'last_name' => 'Doe',
						]
					]
				],
				'test_class3' => [
					'class' => BaseObjectTestClass3::class,
					'arr' => [],
					'expected' => [
						'int' => 1,
						'float' => 1.01,
						'string' => 'This is a string',
						'bool' => true,
						'array' => [
							'first_name' => 'John',
							'last_name' => 'Doe',
						],
						'object' => json_decode(json_encode([
							'first_name' => 'John',
							'last_name' => 'Doe',
						])),
						'obj2' => [
							'int' => 1,
							'float' => 1.01,
							'string' => 'This is a string',
							'bool' => true,
							'array' => [
								'first_name' => 'John',
								'last_name' => 'Doe',
							],
							'object' => json_decode(json_encode([
								'first_name' => 'John',
								'last_name' => 'Doe',
							])),
							'obj2' => [
								'int' => 1,
								'float' => 1.01,
								'string' => 'This is a string',
								'bool' => true,
								'array' => [
									'first_name' => 'John',
									'last_name' => 'Doe',
								],
							]
						]
					]
				],
			];
		}

		public function testGetPropertyNotFoundThrows(): void {
			$this->expectException(ClassPropertyNotFoundException::class);

			$obj = new BaseObjectTestClass1();
			$obj->get('invalid');
		}

		public function testGetSuccess(): void {
			$obj = new BaseObjectTestClass1();

			$this->assertEquals(1, $obj->get('int'));
			$this->assertEquals(1.01, $obj->get('float'));
			$this->assertEquals('This is a string', $obj->get('string'));
			$this->assertEquals(true, $obj->get('bool'));
			$this->assertEqualsCanonicalizing(new BaseObjectTestClass2(), $obj->get('obj2'));
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

			$obj = new BaseObjectTestClass1();
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
			$obj = new BaseObjectTestClass1();

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
					'oldValue' => new BaseObjectTestClass2(),
					'newValue' => new BaseObjectTestClass2(),
				],
			];
		}

	}

	class BaseObjectTestClass1 extends BaseObject {
		protected ?int $int;
		protected ?float $float;
		protected ?string $string;
		protected ?bool $bool;
		protected ?array $array;
		protected ?object $object;
		protected ?BaseObjectTestClass2 $obj2;

		protected function getParams() {
			return [
				'int' => new BaseObjectParamModel(
					1,
					TypeHelper::TYPE_INT,
					true,
					true,
					false
				),
				'float' => new BaseObjectParamModel(
					1.01,
					TypeHelper::TYPE_FLOAT,
					true,
					true,
					false
				),
				'string' => new BaseObjectParamModel(
					'This is a string',
					TypeHelper::TYPE_STRING,
					true,
					true,
					false
				),
				'bool' => new BaseObjectParamModel(
					true,
					TypeHelper::TYPE_BOOL,
					true,
					true,
					false
				),
				'array' => new BaseObjectParamModel(
					[
						'first_name' => 'John',
						'last_name' => 'Doe',
					],
					TypeHelper::TYPE_ARRAY,
					true,
					true,
					false
				),
				'object' => new BaseObjectParamModel(
					json_decode(json_encode([
						'first_name' => 'John',
						'last_name' => 'Doe',
					])),
					TypeHelper::TYPE_OBJECT,
					true,
					true,
					false
				),
				'obj2' => new BaseObjectParamModel(
					new BaseObjectTestClass2(),
					BaseObjectTestClass2::class,
					true,
					true,
					false
				),
			];
		}
	}

	class BaseObjectTestClass2 extends BaseObject {
		protected int $int;
		protected float $float;
		protected string $string;
		protected bool $bool;
		protected array $array;

		protected function getParams() {
			return [
				'int' => new BaseObjectParamModel(
					1,
					TypeHelper::TYPE_STRING,
					true,
					true,
					false
				),
				'float' => new BaseObjectParamModel(
					1.01,
					TypeHelper::TYPE_FLOAT,
					true,
					true,
					false
				),
				'string' => new BaseObjectParamModel(
					'This is a string',
					TypeHelper::TYPE_STRING,
					true,
					true,
					false
				),
				'bool' => new BaseObjectParamModel(
					true,
					TypeHelper::TYPE_BOOL,
					true,
					true,
					false
				),
				'array' => new BaseObjectParamModel(
					[
						'first_name' => 'John',
						'last_name' => 'Doe',
					],
					TypeHelper::TYPE_ARRAY,
					true,
					true,
					false
				),
			];
		}
	}

	class BaseObjectTestClass3 extends BaseObject {
		protected ?int $int;
		protected ?float $float;
		protected ?string $string;
		protected ?bool $bool;
		protected ?array $array;
		protected ?object $object;
		protected ?BaseObjectTestClass1 $obj2;

		protected function getParams() {
			return [
				'int' => new BaseObjectParamModel(
					1,
					TypeHelper::TYPE_STRING,
					true,
					true,
					false
				),
				'float' => new BaseObjectParamModel(
					1.01,
					TypeHelper::TYPE_FLOAT,
					true,
					true,
					false
				),
				'string' => new BaseObjectParamModel(
					'This is a string',
					TypeHelper::TYPE_STRING,
					true,
					true,
					false
				),
				'bool' => new BaseObjectParamModel(
					true,
					TypeHelper::TYPE_BOOL,
					true,
					true,
					false
				),
				'array' => new BaseObjectParamModel(
					[
						'first_name' => 'John',
						'last_name' => 'Doe',
					],
					TypeHelper::TYPE_ARRAY,
					true,
					true,
					false
				),
				'object' => new BaseObjectParamModel(
					json_decode(json_encode([
						'first_name' => 'John',
						'last_name' => 'Doe',
					])),
					TypeHelper::TYPE_OBJECT,
					true,
					true,
					false
				),
				'obj2' => new BaseObjectParamModel(
					new BaseObjectTestClass1(),
					BaseObjectTestClass1::class,
					true,
					true,
					false
				),
			];
		}
	}

	class BaseObjectTestInvalidValidationRuleClass extends BaseObjectTestClass1 {

		protected function getParams() {
			return [
				'int' => new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_INT,
					true,
					true,
					true,
					BaseObjectValidationTypeEnum::NOT_EMPTY
				),
				'float' => new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_FLOAT,
					true,
					true,
					true,
					'TestValidationRule'
				),
			];
		}

	}

	class BaseObjectTestNullableClass extends BaseObjectTestClass1 {

		protected function getParams() {
			return [
				'int' => new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_INT,
					true,
					true,
					true,
					BaseObjectValidationTypeEnum::NOT_EMPTY
				),
				'float' => new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_FLOAT,
					true,
					true,
					true,
					BaseObjectValidationTypeEnum::MISSING
				),
				'string' => new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_STRING,
					true,
					true,
					true,
					BaseObjectValidationTypeEnum::NOT_EMPTY
				),
				'bool' => new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_BOOL,
					true,
					true,
					true,
					BaseObjectValidationTypeEnum::MISSING
				),
				'array' => new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_ARRAY,
					true,
					true,
					true,
					BaseObjectValidationTypeEnum::NOT_EMPTY
				),
				'object' =>  new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_OBJECT,
					false,
					true,
					true,
				),
				'obj2' => new BaseObjectParamModel(
					null,
					BaseObjectTestClass2::class,
					false,
					true,
					true,
				),
			];
		}

	}

	class BaseObjectTestInvalidParamClass extends BaseObjectTestClass1 {

		protected function getParams() {
			return [
				'int' => new BaseObjectParamModel(
					1,
					TypeHelper::TYPE_INT,
					true,
					true,
					false
				),
				'test' => new BaseObjectParamModel(
					1.01,
					TypeHelper::TYPE_FLOAT,
					true,
					true,
					false
				),
			];
		}

	}

	class BaseObjectTestNotEmptyParamClass extends BaseObjectTestClass1 {

		protected function getParams() {
			return [
				'int' => new BaseObjectParamModel(
					1,
					TypeHelper::TYPE_INT,
					true,
					true,
					false
				),
				'float' => new BaseObjectParamModel(
					null,
					TypeHelper::TYPE_FLOAT,
					true,
					true,
					false
				),
			];
		}

	}

	class BaseObjectTestToArrayClass extends BaseObjectTestClass1 {

		protected function getParams() {
			return [
				'int' => new BaseObjectParamModel(
					1,
					TypeHelper::TYPE_STRING,
					true,
					true,
					false
				),
				'float' => new BaseObjectParamModel(
					1.01,
					TypeHelper::TYPE_FLOAT,
					true,
					false,
					false
				),
				'string' => new BaseObjectParamModel(
					'This is a string',
					TypeHelper::TYPE_STRING,
					true,
					true,
					false
				),
				'bool' => new BaseObjectParamModel(
					true,
					TypeHelper::TYPE_BOOL,
					true,
					true,
					false
				),
				'array' => new BaseObjectParamModel(
					[
						'first_name' => 'John',
						'last_name' => 'Doe',
					],
					TypeHelper::TYPE_ARRAY,
					true,
					true,
					false
				),
				'object' => new BaseObjectParamModel(
					json_decode(json_encode([
						'first_name' => 'John',
						'last_name' => 'Doe',
					])),
					TypeHelper::TYPE_OBJECT,
					true,
					false,
					false
				),
				'obj2' => new BaseObjectParamModel(
					new BaseObjectTestClass2(),
					BaseObjectTestClass2::class,
					true,
					false,
					false
				),
			];
		}

	}
