<?php
	namespace DigitalSplash\Media\Models;

	class DocumentsExtensions {

		public const PDF = "pdf";
		public const DOC = "doc";
		public const DOCX = "docx";
		public const XLS = "xls";
		public const XLSX = "xlsx";

		public const EXTENSIONS = [
			self::PDF,
			self::DOC,
			self::DOCX,
			self::XLS,
			self::XLSX,
		];

		public static function getExtensions(): array {
			return self::EXTENSIONS;
		}
	}