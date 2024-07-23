<?php
	namespace DigitalSplash\Core\Models;

	class BaseObjectValidationTypeEnum {
		const MISSING = 'Missing';
		const NOT_EMPTY = 'NotEmpty';

		const ALLOWED = [
			self::MISSING,
			self::NOT_EMPTY
		];
	}
