<?php
	use RawadyMario\Media\Helpers\Upload;

	include_once "../../../../../vendor/autoload.php";

	$singleUploadResp = [];
	$multipleUploadsResp = [];

	$singleUpload = new Upload();
	$singleUpload->SetElemName("singleUpload");
	$singleUpload->SetUploadPath(__DIR__ . "/../../../../_CommonFiles/Upload");
	$singleUpload->SetFolders("SingleUpload/ManualTest/");
	try {
		$singleUpload->Upload();
		$singleUploadResp = $singleUpload->GetUploadResponse();
	}
	catch (Throwable $e) {
		// var_dump($e->getMessage());
	}

	$multipleUploads = new Upload();
	$multipleUploads->SetElemName("multipleUploads");
	$multipleUploads->SetFolders("MultipleUploads/ManualTest");
	$multipleUploads->SetUploadPath(__DIR__ . "/../../../../_CommonFiles/Upload");
	try {
		$multipleUploads->Upload();
		$multipleUploadsResp = $multipleUploads->GetUploadResponse();
	}
	catch (Throwable $e) {
		// var_dump($e->getMessage());
	}

	$resp = array_merge(
		$singleUploadResp,
		$multipleUploadsResp
	);
	if (count($resp) > 0) {
		foreach ($resp AS $rsp) {
			foreach ($rsp AS $k => $v) {
				echo "{$k}: \"{$v}\"<br />";
			}
			echo "<hr />";
		}
	}
?>
<html>
	<head></head>
	<body>
		<h2>List of all Uploaded Files</h2>
		<hr />
		<form method="post" enctype="multipart/form-data">
			<h2>Upload Single File:</h2>
			<input type="file" name="singleUpload" />
			<hr />
			<h2>Upload Multiple Files:</h2>
			<input type="file" name="multipleUploads[]" multiple />
			<hr>
			<button>Submit</button>
		</form>
	</body>
</html>
