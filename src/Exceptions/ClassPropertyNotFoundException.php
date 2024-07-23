<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;
	use DigitalSplash\Models\HttpCode;

	final class ClassPropertyNotFoundException extends BaseException {
		protected $message = "exception.ClassPropertyNotFound";

		public function __construct(
			string $property,
			string $class,
			int $code = 0,
			int $subcode = 0,
			int $responseCode = HttpCode::NOTFOUND
		) {
			parent::__construct($this->message, [
				"::property::" => $property,
				"::class::" => $class,
			], $code, $subcode, $responseCode);
		}
	}
