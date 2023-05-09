<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\UploadException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Media\Models\File;
	use DigitalSplash\Media\Models\Files;
	use DigitalSplash\Models\Code;
	use Throwable;

	class Upload extends Files {

		public const convertToNextGen = false;
		public const Error = 0;
		public const Success = 1;

		private array $allowedExtensions;
		public string $uploadPath; //upload path

			// public $fileFullPath;
		// public $destName; //destination filename
		// public $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		// public $convertToNextGen;
		// public $resize;
		// public $uploadedPaths;
		// public $uploadedData;

		public function __construct(
			array $phpFiles = [],
			array $allowedExtensions = [],
			string $uploadPath = ''
		) {
			$this->allowedExtensions = $allowedExtensions;
			$this->uploadPath = $uploadPath;

			// $this->fileFullPath = "";
			// $this->destName	= "";
			// $this->ratio = 0;
			// $this->convertToNextGen = Upload::convertToNextGen;
			// $this->resize = true;
			// $this->uploadedPaths = [];
			// $this->uploadedData	= [];

			if (Helper::IsNullOrEmpty($this->allowedExtensions)) {
				$this->allowedExtensions = ImagesExtensions::getExtensions();
			}

			if (Helper::IsNullOrEmpty($this->uploadPath)) {
				$this->uploadPath = Media::GetUploadDir();
			}

			parent::__construct($phpFiles);

		}

		public function upload(): array {
			$this->buildFiles();

			$retArr = [];

			foreach ($this->getFiles() as $file) {
				try {
					$this->uploadFile($file);

					$retArr[] = [
						'status' => Code::SUCCESS,
						'fileName' => $file->getName(),
						'elemName' => $file->getElemName(),
					];
				} catch (Throwable $t) {
					$retArr[] = [
						'status' => Code::ERROR,
						'message' => $t->getMessage(),
						'fileName' => $file->getName(),
						'elemName' => $file->getElemName(),
					];
				}
			}

			return $retArr;

			// $files = $this->getFiles();
			// $filesCount = count($files);

			// for ($i=0; $i < $filesCount; $i++) {
			// 	if ($files[$i]['name'] != "" && $this->getError() == 0) {
			// 		if (self::CheckExtensionValidity($files[$i], $this->getAllowedExtensions())) {
			// 			$fileName = self::safeName($files[$i]['name']);
			// 			$uploadPath = $this->uploadPath . $this->folders . $fileName;

			// 			if ($this->isTest) {
			// 				$this->uploadedPaths[] = $uploadPath;
			// 				$this->uploadedData[] = $this->getFiles()[$i];
			// 				$this->successArr[] = $fileName;
			// 			}else {
			// 				$uploadResult = $this->uploadToServer($uploadPath);

			// 				if ($uploadResult['status'] == self::Success) {
			// 					$this->uploadedPaths[] = $uploadPath;
			// 					$this->uploadedData[] = $this->getFiles()[$i];
			// 					$this->successArr[] = $fileName;
			// 				}else {
			// 					$this->errorArr[] = $fileName;
			// 					$this->error = 1;
			// 				}
			// 			}
			// 		}else {
			// 			$this->errorArr[] = $fileName;
			// 			$this->error = 1;
			// 		}
			// 	}
			// }

			// $this->retArr = [
			// 	"success"	=> $this->successArr,
			// 	"error"		=> $this->errorArr,
			// 	"errorFlag"	=> $this->error
			// ];

			// return $this->retArr;
		}

		private function uploadFile(File $file): void {
			$file->validateFile($this->allowedExtensions);

			$extension = pathinfo($file->getName(), PATHINFO_EXTENSION);

			// Helper::CreateFolderRecursive($this->uploadPath)


			// if ($this->destName == "") {
			// 	$this->destName = time() . " " . rand(1000, 9999);
			// }
			// $destNameNoExtension = self::safeName($this->destName);
			// $this->destName		= $destNameNoExtension . "." . $extension;
			// $uploadPath	= $this->uploadPath . $this->folders . $this->destName;

			// $this->uploadedPaths[]	= $this->folders . $this->destName;
			// $this->uploadedData[]	= [
			// 	"path"		=> $this->folders . $this->destName,
			// 	"real_name"	=> $file['name'],
			// 	"real_size"	=> $file['size'],
			// 	"real_type"	=> $file['type'],
			// ];

			// if (in_array($extension, self::imgsExt)) {
			// 	if ($this->ratio > 0) {
			// 		//Upload Original Image
			// 		$originalFileName	= $destNameNoExtension . "-original." . $extension;
			// 		$originalPath		= $this->uploadPath . $this->folders . $originalFileName;
			// 		self::uploadToServer($file['tmp_name'], $originalPath, $originalFileName);

			// 		//Change Ratio
			// 		$this->retArr[] = self::changeImgRatio($this->ratio, $uploadPath, $originalPath);
			// 	}
			// 	else {
			// 		$this->retArr[]	= self::uploadToServer($file['tmp_name'], $uploadPath, $this->destName);
			// 	}

			// 	if ($this->convertToNextGen) {
			// 		$this->retArr[] = self::convertImgToNextGen($uploadPath, "webp", false);
			// 	}

			// 	if ($this->resize) {
			// 		$imgResizeOptions = [
			// 			"hd",
			// 			"ld",
			// 			"th",
			// 		];
			// 		foreach ($imgResizeOptions AS $resizeOption) {
			// 			$resizeRet = self::resizeImg($uploadPath, $resizeOption, $this->convertToNextGen);
			// 			$this->retArr[] = $resizeRet;
			// 		}
			// 	}

			// 	$this->retArr[] = self::generateFacebookImg($uploadPath);
			// }
			// else {
			// 	self::deleteFile($uploadPath);
			// 	$this->retArr[] = self::uploadToServer($file['tmp_name'], $uploadPath, $file['name']);
			// }
		}

		// public static function uploadToServer($uploadPath=""): array {
		// 	$tmpName=self::getTmpName();
		// 	$fileName=self::getName();
		// 	$uploadedFileName	= pathinfo($uploadPath, PATHINFO_BASENAME);

		// 	if (move_uploaded_file($tmpName, $uploadPath)) {
		// 		return [
		// 			"status"	=> self::Success,
		// 			"message"	=> "File successfully uploaded!",
		// 			"fileName"	=> $uploadedFileName
		// 		];
		// 	}else {
		// 		throw new UploadException();
		// 	}
		// }

		// public function uploadFileTrial(File $file){

		// 	if (!isset($file->getName())) {
		// 		throw new UploadException("No file uploaded '");
		// 	}


		// 	if ($file->getError() !== UPLOAD_ERR_OK) {
		// 		$this->handleUploadFileError($file);
		// 		return;
		// 	}

		// 	$filepath = "{"dir path"}/{$file->getName()}";
		// 	move_uploaded_file($file->getTmpName(), $filepath);

		// 	return [
		// 		'name' => $file->getName(),
		// 		'type' => $file->getType(),
		// 		'size' => $file->getSize(),
		// 		'filepath' => $filepath,
		// 	];
		// }



	}
