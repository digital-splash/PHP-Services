<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\UploadException;
	use DigitalSplash\Media\Models\DocumentsExtensions;
	use DigitalSplash\Media\Models\ImagesExtensions;

	class Upload{
		public const convertToNextGen = false;
		public const Error = 0;
		public const Success = 1;

		private $_name;
		private $_type;
		private $_tmp_name;
		private $_error;
		private $_size;
		private $_files;

		// public $fileFullPath;

		// public $elemName; //name attr of the file element
		// public $uploadPath; //upload path
		// public $folders; //folders inside the upload folder
		// public $destName; //destination filename
		private $allowedExtensions; //allowed extensions
		// public $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		// public $convertToNextGen;
		// public $resize;
		private $retArr;
		// public $uploadedPaths;
		// public $uploadedData;
		// public $successArr;
		// public $errorArr;
		// public $error;
		// public $isTest;

		public function __construct() {
			$this->_name = "";
			$this->_type = "";
			$this->_tmp_name = "";
			$this->_error = 0;
			$this->_size = 0;
			// $this->fileFullPath = "";
			// $this->elemName	 = "";
			// $this->uploadPath = "";
			// $this->folders = "";
			// $this->destName	= "";
			$this->allowedExtensions = ImagesExtensions::getExtensions();
			// $this->ratio = 0;
			// $this->convertToNextGen = Upload::convertToNextGen;
			// $this->resize = true;
			$this->retArr = [];
			// $this->uploadedPaths = [];
			// $this->uploadedData	= [];
			// $this->successArr = [];
			// $this->errorArr = [];
			// $this->error = 0;
			// $this->isTest = false;
		}

		public function setName(string $name): void {
			$this->_name = $name;
		}

		public function getName(): string {
			return $this->_name;
		}

		public function setType(string $type): void {
			$this->_type = $type;
		}

		public function getType(): string {
			return $this->_type;
		}

		public function setTmpName(string $tmpName): void {
			$this->_tmp_name = $tmpName;
		}

		public function getTmpName(): string {
			return $this->_tmp_name;
		}

		public function setError(int $error): void {
			$this->_error = $error;
		}

		public function getError(): int {
			return $this->_error;
		}

		public function setSize(int $size): void {
			$this->_size = $size;
		}

		public function getSize(): int {
			return $this->_size;
		}

		public function setFiles(array $files): void {
			$this->_files = $files;
		}

		public function getFiles(): array {
			return $this->_files;
		}

		public function appendToFiles($array): void {
			$this->_files[] = $array;
		}

		public function getAllowedExtensions(): array {
			return $this->allowedExtensions;
		}

		public function setRetArr(array $retArr): void {
			$this->retArr = $retArr;
		}

		public function getRetArr(): array {
			return $this->retArr;
		}

		public function appendToRetArr($string): void {
			$this->retArr[] = $string;
		}


		public function buildFiles($files): void {
			$filesCount = count($files['name']);
			$filesKeys = array_keys($files);

			for ($i=0; $i < $filesCount; $i++) {
				$array = [];
				foreach ($filesKeys as $key) {
					$array[] = $files[$key][$i];
				}
				$this->appendToFiles($array);
			}

		}

		public function upload() {
			$this->buildFiles($_FILES);
			$files = $this->getFiles();
			$filesCount = count($files);

			for ($i=0; $i < $filesCount; $i++) {
				$this->setName($files[$i]['name']);
				$this->setType($files[$i]['type']);
				$this->setTmpName($files[$i]['tmp_name']);
				$this->setError($files[$i]['error']);
				$this->setSize($files[$i]['size']);

				

				if ($files[$i]['name'] != "" && $this->getError() == 0) {
					if (self::CheckExtensionValidity($files[$i], $this->getAllowedExtensions())) {
						$fileName = self::safeName($files[$i]['name']);
						$uploadPath = $this->uploadPath . $this->folders . $fileName;

						if ($this->isTest) {
							$this->uploadedPaths[] = $uploadPath;
							$this->uploadedData[] = $this->getFiles()[$i];
							$this->successArr[] = $fileName;
						}else {
							$uploadResult = $this->uploadToServer($uploadPath);

							if ($uploadResult['status'] == self::Success) {
								$this->uploadedPaths[] = $uploadPath;
								$this->uploadedData[] = $this->getFiles()[$i];
								$this->successArr[] = $fileName;
							}else {
								$this->errorArr[] = $fileName;
								$this->error = 1;
							}
						}
					}else {
						$this->errorArr[] = $fileName;
						$this->error = 1;
					}
				}
			}

			$this->retArr = [
				"success"	=> $this->successArr,
				"error"		=> $this->errorArr,
				"errorFlag"	=> $this->error
			];

			return $this->retArr;
		}

		public static function uploadToServer($uploadPath=""): array {
			$tmpName=self::getTmpName();
			$fileName=self::getName();
			$uploadedFileName	= pathinfo($uploadPath, PATHINFO_BASENAME);

			if (move_uploaded_file($tmpName, $uploadPath)) {
				return [
					"status"	=> self::Success,
					"message"	=> "File successfully uploaded!",
					"fileName"	=> $uploadedFileName
				];
			}else {
				throw new UploadException();
			}
		}

		private function uploadFile($file) {
			if ($file['error'] == 0) {

				if (!is_uploaded_file($file['tmp_name'])) {
					$this->appendToRetArr(new UploadException("File is not uploaded!"));
				}
				else {
					$extension = pathinfo($file['name'], PATHINFO_EXTENSION);

					if (!self::CheckExtensionValidity($file, $this->allowedExtensions)) {
						$allowed = implode(", ", $this->allowedExtensions);
						$this->appendToRetArr(new UploadException("File extension is not allowed!"));
					}
					else {
						self::createFolders($this->uploadPath, $this->folders);

						if ($this->destName == "") {
							$this->destName = time() . " " . rand(1000, 9999);
						}
						$destNameNoExtension = self::safeName($this->destName);
						$this->destName		= $destNameNoExtension . "." . $extension;
						$uploadPath	= $this->uploadPath . $this->folders . $this->destName;

						$this->uploadedPaths[]	= $this->folders . $this->destName;
						$this->uploadedData[]	= [
							"path"		=> $this->folders . $this->destName,
							"real_name"	=> $file['name'],
							"real_size"	=> $file['size'],
							"real_type"	=> $file['type'],
						];

						if (in_array($extension, self::imgsExt)) {
							if ($this->ratio > 0) {
								//Upload Original Image
								$originalFileName	= $destNameNoExtension . "-original." . $extension;
								$originalPath		= $this->uploadPath . $this->folders . $originalFileName;
								self::uploadToServer($file['tmp_name'], $originalPath, $originalFileName);
								
								//Change Ratio
								$this->retArr[] = self::changeImgRatio($this->ratio, $uploadPath, $originalPath);
							}
							else {
								$this->retArr[]	= self::uploadToServer($file['tmp_name'], $uploadPath, $this->destName);
							}

							if ($this->convertToNextGen) {
								$this->retArr[] = self::convertImgToNextGen($uploadPath, "webp", false);
							}

							if ($this->resize) {
								$imgResizeOptions = [
									"hd",
									"ld",
									"th",
								];
								foreach ($imgResizeOptions AS $resizeOption) {
									$resizeRet = self::resizeImg($uploadPath, $resizeOption, $this->convertToNextGen);
									$this->retArr[] = $resizeRet;
								}
							}

							$this->retArr[] = self::generateFacebookImg($uploadPath);
						}
						else {
							self::deleteFile($uploadPath);
							$this->retArr[] = self::uploadToServer($file['tmp_name'], $uploadPath, $file['name']);
						}
					}
				}
			}
			else {
				self::handleUploadFileError();
			}
		}

		private function handleUploadFileError(): void {
			switch ($this->_error) {
				case UPLOAD_ERR_INI_SIZE:
					$this->appendToRetArr(new UploadException("The uploaded file exceeds the upload_max_filesize directive in php.ini"));
				break;

				case UPLOAD_ERR_FORM_SIZE:
					$maxSize	= $_POST["MAX_FILE_SIZE"];
					$maxSizeKb	= round($maxSize / 1024);

					$this->appendToRetArr(new UploadException("The uploaded file is larger than the maximum allowed of $maxSizeKb Kb."));

				break;

				case UPLOAD_ERR_PARTIAL:
					$this->appendToRetArr(new UploadException("The uploaded file was only partially uploaded."));
				break;

				case UPLOAD_ERR_NO_FILE:
					$this->appendToRetArr(new UploadException("No file was uploaded."));
				break;

				case UPLOAD_ERR_NO_TMP_DIR:
					$this->appendToRetArr(new UploadException("Missing a temporary folder."));
				break;

				case UPLOAD_ERR_CANT_WRITE:
					$this->appendToRetArr(new UploadException("Failed to write file to disk."));
				break;

				case UPLOAD_ERR_EXTENSION:
					$this->appendToRetArr(new UploadException("File upload stopped by extension."));
				break;

				default:
					$this->appendToRetArr(new UploadException("Unknown upload error."));
				break;
			}
		}

		public static function safeName($str=""): string {
			return preg_replace("/[-]+/", "-", preg_replace("/[^a-z0-9-]/", "", strtolower(str_replace(" ", "-", $str)))) ;
		}

		public static function CheckExtensionValidity($file, $allowedExtensionsArr=array()) {
				$fileName	= $file['name'];
				$extName	= strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

				if ($fileName != "" && !in_array($extName, $allowedExtensionsArr)) {
					return $fileName;
				}

			return true;
		}
	}