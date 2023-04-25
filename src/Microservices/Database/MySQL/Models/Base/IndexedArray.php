<?php
	namespace DigitalSplash\Database\MySQL\Models\Base;

	use DigitalSplash\Database\MySQL\Helpers\QueryBuilder;
	use DigitalSplash\Database\MySQL\Models\Binds;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;

	class IndexedArray {
		private string $implodeValue;
		private string $statementPrefix;
		protected string $finalString;
		protected array $array;
		public Binds $binds;

		public function __construct(
			string $implodeValue,
			string $statementPrefix
		) {
			if (Helper::StringNullOrEmpty($implodeValue)) {
				throw new NotEmptyParamException('implodeValue');
			}

			$this->implodeValue = $implodeValue;
			$this->statementPrefix = $statementPrefix;
			$this->finalString = '';
			$this->array = [];
			$this->binds = new Binds();
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

		public function getArray(): array {
			return $this->array;
		}

		public function setArray(array $value): void {
			$this->array = $value;
		}

		public function clearArray(): void {
			$this->setArray([]);
		}

		public function appendToArray(string $key, $value): void {
			$this->array[$key] = $value;
		}

		public function appendArrayToArray(array $array): void {
			foreach ($array AS $key => $value) {
				$this->appendToArray($key, $value);
			}
		}

		public function generateStringStatement(): void {
			if (Helper::ArrayNullOrEmpty($this->array)) {
				$this->clearFinalString();
				return;
			}

			$strArr = [];
			foreach ($this->array AS $column => $value) {
				$bind_key = ':' . $column;
				$strArr[] = "`{$column}` = {$bind_key}";

				$this->binds->appendToBinds($bind_key,
					[
						'value' => $value,
						'type' => QueryBuilder::GetPDOTypeFromValue($value)
					]
				);
			}
			$strValue = Helper::ImplodeArrToStr($strArr, $this->implodeValue);
			$this->setFinalString(trim(" " . $this->statementPrefix . " " . $strValue));
		}

	}
