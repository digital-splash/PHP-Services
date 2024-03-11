<?php
	namespace DigitalSplash\Classes\Core\Notification\Handler;

	class SendForgotPassEmailHandler {
		
		public static function Send(array $payload=[], ?array $mainPayload=[]) {
			if (is_null($mainPayload) || count($mainPayload) == 0) {
				$mainPayload = $payload;
			}

			$handler = new Notification_MainHandler();
            $handler->AddDefaults($payload);
			
			$handler->notificationManager->haveEmail = true;
			$handler->notificationManager->SetEmailMainTemplateBoxedWithButton();
			$handler->notificationManager->SetTemplate("user/SendForgotPassEmail");
			
			$handler->notificationManager->SetQueueName("SendForgotPassEmail");
			$handler->notificationManager->SetPayload($mainPayload);
			
			return $handler->notificationManager->Send();
		}

	}
