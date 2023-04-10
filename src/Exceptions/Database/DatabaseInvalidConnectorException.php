<?php
	namespace DigitalSplash\Exceptions\Database;

	use DigitalSplash\Exceptions\Base\BaseParameterException;

	final class DatabaseInvalidConnectorException extends BaseParameterException {
		protected $message = "exception.database.InvalidConnector";
	}
