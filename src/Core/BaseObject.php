<?php
	namespace DigitalSplash\Core;

	use DigitalSplash\Exceptions\ClassPropertyNotFound;
	use ReflectionClass;
	use ReflectionType;
	use ReflectionNamedType;

	class BaseObject {

		/**
		 * Magic Getter Method
		 * @return mixed
		 */
		public function get(string $name) {
			$reflectionClass = new ReflectionClass($this);
			if (!$reflectionClass->hasProperty($name)) {
				throw new ClassPropertyNotFound($name, $reflectionClass::class);
			}

			$property = $reflectionClass->getProperty($name);
			if ($property->isPrivate()) {
				$property->setAccessible(true);
			}
			return $property->getValue($this);
		}

		/**
		 * Magic Setter Method
		 */
		public function set(string $name, $value): void {
			$reflectionClass = new ReflectionClass($this);
			if (!$reflectionClass->hasProperty($name)) {
				throw new ClassPropertyNotFound($name, $reflectionClass::class);
			}

			$property = $reflectionClass->getProperty($name);
			$propertyType = $property->getType();
			// if ($propertyType && $this->validateType($value, $propertyType)) {
			// 	if ($property->isPrivate()) {
			// 		$property->setAccessible(true);
			// 	}
			// 	$property->setValue($this, $value);
			// } else {

			// }
		}

	}
