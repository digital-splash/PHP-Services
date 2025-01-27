<?php

	namespace DigitalSplash\Exceptions\Auth;

	use DigitalSplash\Exceptions\Base\BaseException;
	use DigitalSplash\Models\HttpCode;

	class TokenExpiredException extends BaseException {
		protected $message = 'exception.api.tokenExpired';

		public function __construct() {
			return parent::__construct($this->message, [], 0, 0, HttpCode::UNAUTHORIZED);
		}
	}
