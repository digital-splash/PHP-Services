<?php

	ini_set("memory_limit"			, "1024M"); //1GB
	ini_set("max_execution_time"	, 900); //15 minutes
	include_once __DIR__ . '/../../../../../vendor/autoload.php';

	use DigitalSplash\Media\Helpers\Media;
	use DigitalSplash\Media\Helpers\UploadQueue;

	if (!empty($_FILES)) {
		echo '<pre>';

		Media::SetUploadDir(__DIR__ . "/../../../../_CommonFiles/Upload");

		$upload = new UploadQueue($_FILES, 'test-upload-queue', '///UploadFiles/test', [], 5, true, true, ['all']);
		$results = $upload->upload();
		var_dump($results);


		$queue = [];
		foreach ($results as $result) {
			$queue[] = $result['uploadedFile'];
		}
		Media::SetUploadDir(__DIR__ . "/../../../../_CommonFiles/Upload");
		$upload2 = new UploadQueue([], '', '///UploadFiles/test', [], 5, true, true, ['all']);
		$upload2->processImages($queue);
		echo '</pre>';
	}
?>

<html>
	<head>
		<style>
			.bloc {
				display: block;
				margin-bottom: 25px;
			}

			label,
			input {
				display: block;
				margin-bottom: 5px;
			}

			label {
				font-weight: 600;
			}
		</style>
	</head>
	<body>
		<form action="" method="POST" enctype="multipart/form-data">
			<div class="bloc">
				<label for="file_1">File 1</label>
				<input type="file" name="file_1[]" id="file_1" multiple>
			</div>
			<button>Submit</button>
		</form>
	</body>
</html>
