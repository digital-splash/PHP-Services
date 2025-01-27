<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;
	use DigitalSplash\Models\HttpCode;

	final class InvalidTypeException extends BaseException {
		protected $message = 'exception.validation.invalidType';

		public function __construct(
			string $propertyName,
			string $expectedType,
			       $givenValue,
			int    $code = 0,
			int    $subCode = 0,
			int    $responseCode = HttpCode::NOTFOUND
		) {
			parent::__construct($this->message, [
				'::propertyName::' => $propertyName,
				'::expectedType::' => $expectedType,
				'::givenType::' => gettype($givenValue),
			], $code, $subCode, $responseCode);
		}
	}
