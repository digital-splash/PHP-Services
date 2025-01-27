<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Models\HttpCode;

	final class InvalidArgumentException extends BaseException {
		protected $message = 'exception.validation.invalidArgument';

		public function __construct(
			string  $argument,
			string  $value,
			?string $allowed = null,
			int     $code = 0,
			int     $subcode = 0,
			int     $responseCode = HttpCode::NOTFOUND
		) {
			if (!Helper::IsNullOrEmpty($allowed)) {
				$this->message = 'exception.validation.invalidArgumentWithAllowed';
			}

			parent::__construct($this->message, [
				'::argument::' => $argument,
				'::value::' => $value,
				'::allowed::' => $allowed,
			], $code, $subcode, $responseCode);
		}
	}
