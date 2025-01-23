<?php

	namespace DigitalSplash\Core;

	use DigitalSplash\Core\Models\BaseObjectParamModel;
	use DigitalSplash\Core\Models\BaseObjectValidationTypeEnum;
	use DigitalSplash\Exceptions\ClassPropertyNotFoundException;
	use DigitalSplash\Exceptions\InvalidArgumentException;
	use DigitalSplash\Exceptions\InvalidParamException;
	use DigitalSplash\Exceptions\InvalidTypeException;
	use DigitalSplash\Exceptions\MissingParamsException;
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

		/**
		 * @return BaseObjectParamModel[]
		 */
		abstract protected function getParams();

		public function __construct(
			array $arr = []
		) {
			$this->PARAMS = $this->getParams();

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
				if (!$paramModel->isRequired()) {
					continue;
				}

				$validationRule = $paramModel->getValidationRule();
				if (!in_array($validationRule, BaseObjectValidationTypeEnum::ALLOWED)) {
					throw new InvalidArgumentException('validationRule', $validationRule, implode(', ', BaseObjectValidationTypeEnum::ALLOWED));
				}

				$value = $this->get($param);

				$required[$validationRule][] = $param;
				if (
					$validationRule === BaseObjectValidationTypeEnum::NOT_EMPTY
					|| (
						$validationRule === BaseObjectValidationTypeEnum::MISSING
						&& !is_null($value)
					)
				) {
					$params[$validationRule][$param] = $this->get($param);
				}
			}

			$allMissing = [];
			foreach ($required as $validationRule => $_required) {
				$_params = $params[$validationRule] ?? [];

				switch ($validationRule) {
					case BaseObjectValidationTypeEnum::MISSING:
						[
							'missing' => $missingParams,
						] = Helper::MissingParams($_params, $_required);
						$allMissing = array_merge($allMissing, $missingParams);
						break;

					case BaseObjectValidationTypeEnum::NOT_EMPTY:
						[
							'missing' => $missingParams,
						] = Helper::MissingNotEmptyParams($_params, $_required);
						$allMissing = array_merge($allMissing, $missingParams);
						break;
				}
			}

			if (!empty($allMissing)) {
				throw new MissingParamsException($allMissing);
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

					} catch (Throwable $th) {
					}

					$arr[$param] = $value;
				}
			}

			return $arr;
		}

		/**
		 * Magic Getter Method
		 *
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
