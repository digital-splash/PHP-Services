<?php
	namespace DigitalSplash\Core\Models;

	class BaseObjectValidationTypeEnum {
		/**
		 * Validate only if it's missing. Exclude NULL
		 */
		const MISSING = 'Missing';

		/**
		 * Validate is it's missing, and if it is NULL
		 */
		const NOT_EMPTY = 'NotEmpty';

		const ALLOWED = [
			self::MISSING,
			self::NOT_EMPTY
		];
	}
