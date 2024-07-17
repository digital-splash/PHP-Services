<?php
	namespace DigitalSplash\Core;

	use DigitalSplash\Exceptions\ClassPropertyNotFoundException;
	use DigitalSplash\Exceptions\InvalidTypeException;
	use DigitalSplash\Helpers\TypeHelper;
	use ReflectionClass;

	class BaseObject {

		/**
		 * Magic Getter Method
		 * @return mixed
		 */
		public function get(string $name) {
			$reflectionClass = new ReflectionClass($this);
			if (!$reflectionClass->hasProperty($name)) {
				throw new ClassPropertyNotFoundException($name, $this::class);
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
				throw new ClassPropertyNotFoundException($name, $this::class);
			}

			$propertyType = TypeHelper::getClassPropertyType($this::class, $name);
			if (!TypeHelper::IsOfType($value, $propertyType)) {
				throw new InvalidTypeException($name, $propertyType, $value);
			}

			$property = $reflectionClass->getProperty($name);
			if ($property->isPrivate()) {
				$property->setAccessible(true);
			}
			$property->setValue($this, $value);
		}

	}
