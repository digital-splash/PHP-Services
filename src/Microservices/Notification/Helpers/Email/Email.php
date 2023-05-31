<?php
	namespace DigitalSplash\Notification\Helpers\Email;

	use DigitalSplash\Models\Status;
	use DigitalSplash\Notification\Interfaces\INotification;
	use DigitalSplash\Notification\Models\Notification as NotificationModel;
	use Throwable;

	class Email implements INotification {
		public NotificationModel $model;

		public function __construct() {
			$this->model = new NotificationModel();
		}

		public function send(): array {
			$returnArray = [
				'status' => Status::ERROR
			];

			try {
				$this->sendPhpMailer();

				$returnArray['status'] = Status::SUCCESS;
			} catch (Throwable $t) {
				$returnArray['message'] = $t->getMessage();
			}

			return $returnArray;
		}

		public function sendPhpMailer(): void {
			$phpMailer = new PhpMailer();
			$phpMailer->model = $this->model;
			$phpMailer->send();
		}
	}
