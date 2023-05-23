<?php
	namespace DigitalSplash\Media\Interface;


	interface IImageModify {

		public function validateParams(): void;

		public function save(): void;
	}
