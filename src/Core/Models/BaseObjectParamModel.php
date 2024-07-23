<?php
	namespace DigitalSplash\Core\Models;

	class BaseObjectParamModel {
		private $defaultValue;
		private string $type;
		private bool $required;
		private bool $inArray;
		private bool $nullable;

		public function __construct(
			$defaultValue,
			string $type,
			bool $required,
			bool $inArray,
			bool $nullable
		) {
			$this->defaultValue = $defaultValue;
			$this->type = $type;
			$this->required = $required;
			$this->inArray = $inArray;
			$this->nullable = $nullable;
		}

		public function getDefaultValue() {
			return $this->defaultValue;
		}

		public function getType() {
			return $this->type;
		}

		public function isRequired() {
			return $this->required;
		}

		public function isInArray(): bool {
			return $this->inArray;
		}

		public function isNullable(): bool {
			return $this->nullable;
		}

	}
