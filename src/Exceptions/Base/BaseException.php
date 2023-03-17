<?php

namespace DigitalSplash\Exceptions\Base;

use Exception;
use DigitalSplash\Helpers\Helper;
use DigitalSplash\Language\Helpers\Translate;

class BaseException extends Exception
{

	public function __construct(
		string $message = "",
		array $replace = []
	) {
		if (!Helper::StringNullOrEmpty($message)) {
			$this->message = $message;
		}

		$this->message = Translate::TranslateString($this->message, null, $replace);
		parent::__construct();
	}
}
