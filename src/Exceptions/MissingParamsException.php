<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseParameterException;
	use DigitalSplash\Models\HttpCode;

	class MissingParamsException extends BaseParameterException {
		protected $message = "Missing Parameter(s): ::params::";

		public function __construct(
			array $params,
			int $code = 0,
			int $subcode = 0,
			int $responseCode = HttpCode::NOTFOUND
		) {
			$paramsStr = "`" . implode('`, `', $params) . "`";
			parent::__construct($paramsStr, $code, $subcode, $responseCode);
		}
	}
