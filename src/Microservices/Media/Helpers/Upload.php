<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\UploadException;
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

		// public $fileFullPath;
		// public $uploadPath; //upload path
		// public $folders; //folders inside the upload folder
		// public $destName; //destination filename
		// public $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		// public $convertToNextGen;
		// public $resize;
		// public $uploadedPaths;
		// public $uploadedData;

		public function __construct(
			array $phpFiles
		) {
			$this->allowedExtensions = ImagesExtensions::getExtensions();

			// $this->fileFullPath = "";
			// $this->elemName	 = "";
			// $this->uploadPath = "";
			// $this->folders = "";
			// $this->destName	= "";
			// $this->ratio = 0;
			// $this->convertToNextGen = Upload::convertToNextGen;
			// $this->resize = true;
			// $this->uploadedPaths = [];
			// $this->uploadedData	= [];

			parent::__construct($phpFiles);
		}

		public function setAllowedExtensions(array $array): void {
			$this->allowedExtensions = $array;
		}

		public function getAllowedExtensions(): array {
			return $this->allowedExtensions;
		}

		public function appendToAllowedExtensions($array): void {
			$this->allowedExtensions[] = $array;
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

		private function uploadFile(File $file): void {
			throw new UploadException('This is a test throw message');
		}

		// private function uploadFile_old($file) {
		// 	if ($file['error'] == 0) {

		// 		if (!is_uploaded_file($file['tmp_name'])) {
		// 			$this->appendToRetArr(new UploadException("File is not uploaded!"));
		// 		}
		// 		else {
		// 			$extension = pathinfo($file['name'], PATHINFO_EXTENSION);

		// 			if (!self::CheckExtensionValidity($file, $this->allowedExtensions)) {
		// 				$allowed = implode(", ", $this->allowedExtensions);
		// 				$this->appendToRetArr(new UploadException("File extension is not allowed!"));
		// 			}
		// 			else {
		// 				self::createFolders($this->uploadPath, $this->folders);

		// 				if ($this->destName == "") {
		// 					$this->destName = time() . " " . rand(1000, 9999);
		// 				}
		// 				$destNameNoExtension = self::safeName($this->destName);
		// 				$this->destName		= $destNameNoExtension . "." . $extension;
		// 				$uploadPath	= $this->uploadPath . $this->folders . $this->destName;

		// 				$this->uploadedPaths[]	= $this->folders . $this->destName;
		// 				$this->uploadedData[]	= [
		// 					"path"		=> $this->folders . $this->destName,
		// 					"real_name"	=> $file['name'],
		// 					"real_size"	=> $file['size'],
		// 					"real_type"	=> $file['type'],
		// 				];

		// 				if (in_array($extension, self::imgsExt)) {
		// 					if ($this->ratio > 0) {
		// 						//Upload Original Image
		// 						$originalFileName	= $destNameNoExtension . "-original." . $extension;
		// 						$originalPath		= $this->uploadPath . $this->folders . $originalFileName;
		// 						self::uploadToServer($file['tmp_name'], $originalPath, $originalFileName);

		// 						//Change Ratio
		// 						$this->retArr[] = self::changeImgRatio($this->ratio, $uploadPath, $originalPath);
		// 					}
		// 					else {
		// 						$this->retArr[]	= self::uploadToServer($file['tmp_name'], $uploadPath, $this->destName);
		// 					}

		// 					if ($this->convertToNextGen) {
		// 						$this->retArr[] = self::convertImgToNextGen($uploadPath, "webp", false);
		// 					}

		// 					if ($this->resize) {
		// 						$imgResizeOptions = [
		// 							"hd",
		// 							"ld",
		// 							"th",
		// 						];
		// 						foreach ($imgResizeOptions AS $resizeOption) {
		// 							$resizeRet = self::resizeImg($uploadPath, $resizeOption, $this->convertToNextGen);
		// 							$this->retArr[] = $resizeRet;
		// 						}
		// 					}

		// 					$this->retArr[] = self::generateFacebookImg($uploadPath);
		// 				}
		// 				else {
		// 					self::deleteFile($uploadPath);
		// 					$this->retArr[] = self::uploadToServer($file['tmp_name'], $uploadPath, $file['name']);
		// 				}
		// 			}
		// 		}
		// 	}
		// 	else {
		// 		self::handleUploadFileError();
		// 	}
		// }

		// private function handleUploadFileError(): void {
		// 	switch ($this->_error) {
		// 		case UPLOAD_ERR_INI_SIZE:
		// 			$this->appendToRetArr(new UploadException("The uploaded file exceeds the upload_max_filesize directive in php.ini"));
		// 		break;

		// 		case UPLOAD_ERR_FORM_SIZE:
		// 			$maxSize	= $_POST["MAX_FILE_SIZE"];
		// 			$maxSizeKb	= round($maxSize / 1024);

		// 			$this->appendToRetArr(new UploadException("The uploaded file is larger than the maximum allowed of $maxSizeKb Kb."));

		// 		break;

		// 		case UPLOAD_ERR_PARTIAL:
		// 			$this->appendToRetArr(new UploadException("The uploaded file was only partially uploaded."));
		// 		break;

		// 		case UPLOAD_ERR_NO_FILE:
		// 			$this->appendToRetArr(new UploadException("No file was uploaded."));
		// 		break;

		// 		case UPLOAD_ERR_NO_TMP_DIR:
		// 			$this->appendToRetArr(new UploadException("Missing a temporary folder."));
		// 		break;

		// 		case UPLOAD_ERR_CANT_WRITE:
		// 			$this->appendToRetArr(new UploadException("Failed to write file to disk."));
		// 		break;

		// 		case UPLOAD_ERR_EXTENSION:
		// 			$this->appendToRetArr(new UploadException("File upload stopped by extension."));
		// 		break;

		// 		default:
		// 			$this->appendToRetArr(new UploadException("Unknown upload error."));
		// 		break;
		// 	}
		// }

		// public static function CheckExtensionValidity($file, $allowedExtensionsArr=array()) {
		// 		$fileName	= $file['name'];
		// 		$extName	= strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

		// 		if ($fileName != "" && !in_array($extName, $allowedExtensionsArr)) {
		// 			return $fileName;
		// 		}

		// 	return true;
		// }
	}
