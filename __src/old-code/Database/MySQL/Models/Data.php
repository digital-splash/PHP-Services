<?php
	namespace OldCode\DigitalSplash\Database\MySQL\Models;

	class Data {
		protected array $data;

		public function __construct() {
			$this->data = [];
		}

		public function getData(): array {
			return $this->data;
		}

		public function setData(array $data): void {
			$this->data = $data;
		}

		public function clearData(): void {
			$this->setData([]);
		}

		public function appendToData(string $key, $value): void {
			$this->data[$key] = $value;
		}

		public function appendArrayToData(array $data): void {
			foreach ($data as $key => $value) {
				$this->appendToData($key, $value);
			}
		}

	}
