<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseException;
	use DigitalSplash\Models\HttpCode;

	final class InvalidTypeException extends BaseException {
		protected $message = "exception.InvalidType";

		public function __construct(
			string $propertyName,
			string $expectedType,
			$givenValue,
			int $code = 0,
			int $subcode = 0,
			int $responseCode = HttpCode::NOTFOUND
		) {
			parent::__construct($this->message, [
				"::propertyName::" => $propertyName,
				"::expectedType::" => $expectedType,
				"::givenType::" => gettype($givenValue),
			], $code, $subcode, $responseCode);
		}
	}
