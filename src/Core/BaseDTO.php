<?php
	namespace DigitalSplash\Core;

	abstract class BaseDTO extends BaseObject {

		abstract protected function validate(): void;
	}
