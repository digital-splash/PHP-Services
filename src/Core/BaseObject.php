<?php
	namespace DigitalSplash\Core;

	use DigitalSplash\Core\Models\BaseObjectParamModel;
	use DigitalSplash\Exceptions\ClassPropertyNotFoundException;
	use DigitalSplash\Exceptions\InvalidParamException;
	use DigitalSplash\Exceptions\InvalidTypeException;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Helpers\TypeHelper;
	use ReflectionClass;
	use Throwable;

	abstract class BaseObject extends Serializer {
		/**
		 * @var BaseObjectParamModel[]
		 */
		protected $PARAMS;

		abstract protected function setParams(): void;

		public function __construct(array $arr = []) {
			static::setParams();

			foreach ($this->PARAMS as $param => $paramModel) {
				if (!property_exists(static::class, $param)) {
					throw new InvalidParamException($param);
				}

				$value = $arr[$param] ?? $paramModel->getDefaultValue();

				if (is_null($value) && !$paramModel->isNullable()) {
					throw new NotEmptyParamException($param);
				}

				if (
					!Helper::IsNullOrEmpty($value)
					&& $value !== $paramModel->getDefaultValue()
					&& !TypeHelper::IsOfType($value, $paramModel->getType())
				) {
					throw new InvalidTypeException($param, $paramModel->getType(), $value);
				}

				$this->set($param, $value);
			}

			$this->validate();
		}

		public function validate(): void {
			$required = [];
			$params = [];

			foreach ($this->PARAMS as $param => $paramModel) {
				if ($paramModel->isRequired()) {
					$value = $this->get($param);

					$required[] = $param;
					if (!is_null($value)) {
						$params[$param] = $this->get($param);
					}
				}
			}

			if (!Helper::IsNullOrEmpty($params)) {
				Helper::MissingParamsThrows($params, $required);
			}
		}

		public function toArray(): array {
			$arr = [];

			foreach ($this->PARAMS as $param => $paramModel) {
				if ($paramModel->isInArray()) {
					$value = $this->get($param);

					try {
						$reflectionClass = new ReflectionClass($paramModel->getType());
						while ($reflectionClass->getParentClass()) {
							$reflectionClass = new ReflectionClass($reflectionClass->getParentClass()->getName());
							if ($reflectionClass->getName() === BaseObject::class) {
								$reflectionClass = null;
								$value = $value->toArray();
							}
						}

					} catch (Throwable $th) {}


					$arr[$param] = $value;
				}
			}

			return $arr;
		}

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
			if (!$property->isPublic()) {
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
			if (!$property->isPublic()) {
				$property->setAccessible(true);
			}
			$property->setValue($this, $value);
		}

	}
