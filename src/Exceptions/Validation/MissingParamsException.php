<?php

	namespace DigitalSplash\Exceptions\Validation;

	use DigitalSplash\Exceptions\Base\BaseParameterException;
	use DigitalSplash\Models\HttpCode;

	class MissingParamsException extends BaseParameterException {
		protected $message = 'exception.validation.missingParameters';

		public function __construct(
			array $params,
			int   $code = 0,
			int   $subCode = 0,
			int   $responseCode = HttpCode::NOTFOUND
		) {
			$paramsStr = "`" . implode('`, `', $params) . "`";
			parent::__construct($paramsStr, $code, $subCode, $responseCode);
		}
	}
