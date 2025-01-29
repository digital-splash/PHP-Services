<?php

	namespace DigitalSplash\Microservices\Language\DTO;

	use DigitalSplash\Core\Serializer;
	use DigitalSplash\Microservices\Language\Models\Direction;

	class Language extends Serializer {
		public string $code;
		public string $name;
		public string $direction;
		public bool $isDefault;
		public bool $isActive;

		public function __construct(
			string $code,
			string $name,
			string $direction = Direction::LTR,
			bool   $isDefault = false,
			bool   $isActive = false
		) {
			$this->code = $code;
			$this->name = $name;
			$this->direction = $direction;
			$this->isDefault = $isDefault;
			$this->isActive = $isActive;
		}

		public function toArray(): array {
			return [
				'code' => $this->code,
				'name' => $this->name,
				'direction' => $this->direction,
				'isDefault' => $this->isDefault,
				'isActive' => $this->isActive,
			];
		}
	}