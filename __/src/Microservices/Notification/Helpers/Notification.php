<?php
	namespace DigitalSplash\Notification\Helpers;

	use DigitalSplash\Models\Status;
	use DigitalSplash\Notification\Helpers\Email\Email;
	use DigitalSplash\Notification\Interfaces\INotification;
	use DigitalSplash\Notification\Models\Notification as NotificationModel;
	use Throwable;

	class Notification implements INotification {
		public NotificationModel $model;

		private bool $sendEmail;
		private bool $sendSms;
		private bool $sendWebNotification;
		private bool $sendMobileNotification;

		public function __construct() {
			$this->model = new NotificationModel();
			$this->sendEmail = false;
			$this->sendSms = false;
			$this->sendWebNotification = false;
			$this->sendMobileNotification = false;
		}

		public function setSendEmail(bool $val): void {
			$this->sendEmail = $val;
		}

		public function setSendSms(bool $val): void {
			$this->sendSms = $val;
		}

		public function setSendWebNotification(bool $val): void {
			$this->sendWebNotification = $val;
		}

		public function setSendMobileNotification(bool $val): void {
			$this->sendMobileNotification = $val;
		}

		public function send(): array {
			$emailResponse = [];

			if ($this->sendEmail) {
				try {
					$email = new Email();
					$email->model = $this->model;
					$emailResponse = $email->send();
				} catch (Throwable $t) {
					$emailResponse = [
						'status' => Status::ERROR,
						'message' => $t->getMessage()
					];
				}
			}

			return [
				'email' => $emailResponse
			];
		}

	}
