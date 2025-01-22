<?php
	namespace DigitalSplash\Media\Models;

	class DocumentsExtensions {
		public const PDF = "pdf";
		public const DOC = "doc";
		public const DOCX = "docx";
		public const XLS = "xls";
		public const XLSX = "xlsx";

		public static function getExtensions(): array {
			return [
				self::PDF,
				self::DOC,
				self::DOCX,
				self::XLS,
				self::XLSX,
			];
		}
	}
