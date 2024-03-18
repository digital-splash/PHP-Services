<?php
	namespace OldCode\DigitalSplash\Database\MySQL\Models\Base;

	class SingleValue {
		private string $statementPrefix;
		protected string $finalString;
		protected $value;

		public function __construct(
			string $statementPrefix
		) {
			$this->statementPrefix = $statementPrefix;
			$this->finalString = '';
			$this->value = null;
		}

		public function getFinalString(): string {
			return $this->finalString;
		}

		public function setFinalString(string $value): void {
			$this->finalString = $value;
		}

		public function clearFinalString(): void {
			$this->setFinalString('');
		}

		public function getValue() {
			return $this->value;
		}

		public function setValue($value): void {
			$this->value = $value;
		}

		public function clearValue(): void {
			$this->setValue(null);
		}

		public function generateStringStatement(): void {
			if (empty($this->value)) {
				$this->clearFinalString();
				return;
			}

			$this->setFinalString(trim(" " . $this->statementPrefix . " " . $this->value));
		}

	}
