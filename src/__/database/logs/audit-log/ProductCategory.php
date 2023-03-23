<?php
	namespace DigitalSplash\Classes\Database\Logs\AuditLog;

	use DigitalSplash\Classes\Database\Logs\AuditLog;

	class ProductCategory_AuditLog implements ProductCategory_AuditLogInterface {
		const Type = AuditLog::TypeProductCategory;

		public static function Create(int $recordId, array $payload=[]) : void {
			AuditLog::Add(
				self::Type,
				AuditLog::ActionCreate,
				$recordId,
				$payload
			);
		}

		public static function Edit(int $recordId, array $payload=[]) : void {
			AuditLog::Add(
				self::Type,
				AuditLog::ActionEdit,
				$recordId,
				$payload
			);
		}

		public static function Delete(int $recordId, array $payload=[]) : void {
			AuditLog::Add(
				self::Type,
				AuditLog::ActionDelete,
				$recordId,
				$payload
			);
		}

		public static function Activate(int $recordId, array $payload=[]) : void {
			AuditLog::Add(
				self::Type,
				AuditLog::ActionActivate,
				$recordId,
				$payload
			);
		}

	}
