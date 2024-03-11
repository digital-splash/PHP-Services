<?php
	namespace DigitalSplash\Classes\Core\Notification\Resolver;

	use DigitalSplash\Classes\Helpers\Helper;

	class SendPaymentErrorEmailToUserResolver {
		
		public static function GetData(array $payload) {
			$payload["amount"] = Helper::AddCurrency($payload["amount"] ?? "");
			$payload["remote_id"] = $payload["remote_id"] ?? "N/A";
			$payload["error_reason"] = "N/A";
			$payload["subject"] = "Payment Failed";

			return $payload;
		}

	}
