<?php
	namespace DigitalSplash\Helpers;

	use ReflectionClass;
	use ReflectionNamedType;
	use Throwable;

	class TypeHelper {
		const TYPE_INT = 'integer';
		const TYPE_FLOAT = 'float';
		const TYPE_DOUBLE = 'double';
		const TYPE_NUMERIC = 'numeric';
		const TYPE_STRING = 'string';
		const TYPE_BOOL = 'boolean';
		const TYPE_ARRAY = 'array';
		const TYPE_OBJECT = 'object';

		private static $typeMap = [
			'int' => self::TYPE_INT,
			'bool' => self::TYPE_BOOL,
		];

		/**
		 * @return ReflectionClass|string|null
		 */
		public static function getClassPropertyType(
			string $className,
			string $propertyName
		) {
			try {
				$reflectionClass = new ReflectionClass($className);
				if (!$reflectionClass->hasProperty($propertyName)) {
					return null;
				}
			} catch (Throwable $th) {
				return null;
			}

			$property = $reflectionClass->getProperty($propertyName);
			$type = $property->getType();

			return $type;
		}

		/**
		 * Checks if the given value is of the given type
		 */
		public static function IsOfType(
			$value,
			$type,
			?bool $nullable = null
		): bool {
			if ($type instanceof ReflectionNamedType) {
				$expectedType = $type->getName();
				$nullable = $type->allowsNull();

				// Handle non-built-in types (Custom Classes)
				if (!$type->isBuiltin()) {
					if (is_null($value) && $nullable) {
						return true;
					}
					return $value instanceof $expectedType;
				}

				if (array_key_exists($expectedType, self::$typeMap)) {
					$expectedType = self::$typeMap[$expectedType];
				}
				return self::IsOfType($value, $expectedType, $nullable);
			}

			// if ($type === 'array') {
			// 	if (!is_array($value)) {
			// 		return false;
			// 	}

			// 	// if (empty($value)) return true;
			// 	// $firstElement = reset($value);
			// 	// $elementType = is_object($firstElement) ? get_class($firstElement) : gettype($firstElement);
			// 	// foreach ($value as $item) {
			// 	// 	if (!(is_object($item) && $item instanceof $elementType)) {
			// 	// 		return false;
			// 	// 	}
			// 	// }
			// 	return true;
			// }

			if ($nullable && is_null($value)) {
				return true;
			}

			// Handle built-in types (int, float, string, bool)
			switch ($type) {
				case self::TYPE_INT:
					return gettype($value) === 'integer';

				case TypeHelper::TYPE_FLOAT:
				case TypeHelper::TYPE_DOUBLE:
					return gettype($value) === 'double' || gettype($value) === 'integer';

				case 'numeric':
					return
						gettype($value) === 'string' && is_numeric($value)
						|| gettype($value) === 'integer'
						|| gettype($value) === 'double';

				case TypeHelper::TYPE_STRING:
					return gettype($value) === 'string';

				case TypeHelper::TYPE_BOOL:
					return gettype($value) === 'boolean';

				case TypeHelper::TYPE_ARRAY:
					return gettype($value) === 'array';

				case TypeHelper::TYPE_OBJECT:
					return gettype($value) === 'object';

				default:
					return false;
			}
		}
	}
