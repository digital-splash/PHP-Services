<?php
	namespace DigitalSplash\Core;

	use JsonSerializable;

	abstract class Serializer implements JsonSerializable {

		/**
		 * @param array $arr
		 * @return static
		 */
		abstract public static function arrayDeserialize(array $arr): self;
		abstract public function toArray(): array;

		public function jsonSerialize() {
			return $this->toArray();
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
