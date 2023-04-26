<?php
	namespace DigitalSplash\Database\MySQL\Models;

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

		public function appendToBinds(string $key, $value): void {
			$this->binds[$key] = $value;
		}

		public function appendArrayToBinds(array $binds): void {
			foreach ($binds as $key => $value) {
				$this->appendToBinds($key, $value);
			}
		}

	}
