<?php

	namespace DigitalSplash\Core;

	use JsonSerializable;

	abstract class Serializer implements JsonSerializable {
		abstract public function toArray(): array;

		public function jsonSerialize() {
			return $this->toArray();
		}

		/**
		 * @param array $arr
		 * @return static
		 */
		public static function arrayDeserialize(array $arr): static {
			return new static(...$arr);
		}

		/**
		 * @param array $arr
		 * @return static[]
		 */
		public static function deserializeMultiple(array $arr): array {
			$ret = [];

			foreach ($arr as $item) {
				$ret[] = static::arrayDeserialize($item);
			}

			return $ret;
		}
	}
