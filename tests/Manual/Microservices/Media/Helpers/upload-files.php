<?php

	use DigitalSplash\Media\Helpers\Ratio;
	use DigitalSplash\Media\Helpers\Resize;
	use DigitalSplash\Media\Interface\IImageModify;

	ini_set("memory_limit"			, "1024M"); //1GB
	ini_set("max_execution_time"	, 900); //15 minutes
	include_once __DIR__ . '/../../../../../vendor/autoload.php';

	// class Test {
	//     private IImageModify $_mediaModify;

	//     public function __construct(
	//         IImageModify $mediaModify
	//     ) {
	//         $this->_mediaModify = $mediaModify;
	//     }

	//     public function save() {
	//         $this->_mediaModify->save();
	//     }
	// }

	// $ratio = new Ratio('');
	// $test1 = new Test($ratio);

	// $resize = new Resize('', '', 1);
	// $test2 = new Test($resize);

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Helpers\Media;
	use DigitalSplash\Media\Helpers\Upload;

	if (!empty($_FILES)) {
		echo '<pre>';

		Media::SetUploadDir(__DIR__ . "/../../../../_CommonFiles/Upload");

		$upload = new Upload($_FILES, 'test-upload', '///UploadFiles/test', [], 5, true);
		$result = $upload->upload();
		var_dump($result);


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
