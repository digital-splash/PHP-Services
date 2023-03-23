<?php
	namespace DigitalSplash\Classes\Core\Notification\Resolver;

	use DigitalSplash\Classes\Helpers\Helper;

	class SendPaymentSuccessEmailToUserResolver {
		
		public static function GetData(array $payload) {
			$payload["amount"] = Helper::AddCurrency($payload["amount"] ?? "");
			$payload["remote_id"] = $payload["remote_id"] ?? "N/A";
			$payload["subject"] = "Payment Confirmed";

			return $payload;
		}

	}
