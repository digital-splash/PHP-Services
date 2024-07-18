<?php
	namespace DigitalSplash\Core;

	abstract class BaseDTO extends BaseObject {

		abstract public function validate(): void;
	}
