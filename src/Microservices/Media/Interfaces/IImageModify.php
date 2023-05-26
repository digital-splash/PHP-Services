<?php
	namespace DigitalSplash\Media\Interfaces;


	interface IImageModify {

		public function validateParams(): void;

		public function save(): void;
	}
