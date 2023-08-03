<?php
	namespace DigitalSplash\Exceptions;

	use DigitalSplash\Exceptions\Base\BaseParameterException;
	use DigitalSplash\Models\HttpCode;

	class MissingParamsException extends BaseParameterException {
		protected $message = "Missing Parameter(s): ::params::";

		public function __construct(
			array $params
		) {
			$paramsStr = "`" . implode('`, `', $params) . "`";
			parent::__construct($paramsStr, [], HttpCode::UNPROCESSABLE);
		}
	}
