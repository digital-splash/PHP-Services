<?php
	namespace DigitalSplash\Classes\Cronjob;

	use DigitalSplash\Classes\Database\Queue as CoreQueue;

	class Queue {

		public static function Execute() : array {
			$pendingQueues = CoreQueue::GetPending();
			
			$retArr = [
				"resultsArr" => [],
				"extraInfoArr" => []
			];
			foreach ($pendingQueues AS $pendingQueue) {
				$retArr["resultsArr"][$pendingQueue["id"]] = CoreQueue::Execute($pendingQueue["id"]);
				$retArr["extraInfoArr"][$pendingQueue["id"]] = $pendingQueue;
			}

			return $retArr;
		}

	}

?>
