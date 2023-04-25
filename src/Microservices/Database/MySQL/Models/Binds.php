<?php
	namespace DigitalSplash\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Helpers\QueryBuilder;

	//TODO: Fix the way we are appending binds
	class Binds {
		protected array $binds;

		public function __construct() {
			$this->binds = [];
		}

		public function getBinds(): array {
			return $this->binds;
		}

		public function setBinds(array $binds): void {
			$this->binds = $binds;
		}

		public function clearBinds(): void {
			$this->setBinds([]);
		}

		public function appendToBinds(string $key, $value, ?int $type = null): void {
			if (empty($type)) {
				$type = QueryBuilder::GetPDOTypeFromValue($value);
			}
			$this->binds[$key] = [
				'value' => $value,
				'type' => $type
			];
		}

		public function appendArrayToBinds(array $binds): void {
			foreach ($binds as $key => $bind) {
				$this->appendToBinds($key, $bind['value'], $bind['type'] = null);
			}
		}

	}
