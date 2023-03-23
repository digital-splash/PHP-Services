<?php
	namespace DigitalSplash\Classes\Core\Notification\Resolver;

	use DigitalSplash\Classes\Database\User;
	use DigitalSplash\Classes\Helpers\Helper;

	class SendWelcomeEmailResolver {
		
		public static function GetData(array $payload) {
			$userId = $payload["user_id"];

			$user = new User($userId);

			$authNb	= Helper::GenerateRandomKey(256, true, true, false, "en");
			$user->update([
				"auth_nb"	=> $authNb
			]);

			$confirmLink = getFullUrl(PAGE_VERIFY, LANG, [], ["email"=>$user->row["email"], "key"=>$authNb], WEBSITE_ROOT);
			$subject = "Welcome to " . CLIENT_NAME;

			$payload["button_text"] = "Verify My Account";
			$payload["url"] = $confirmLink;
			$payload["subject"] = $subject;

			return $payload;
		}

	}
