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
				// $this->uploadPath = "mediafiles/";
				// $this->uploadPath = "C:\wamp64\www\PHP-Services\\tests\Manual\Microservices\Media\Helpers";
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

		private function uploadFile(File $file): array {
			$file->validateFile($this->allowedExtensions);

			$extension = pathinfo($file->getName(), PATHINFO_EXTENSION);

			Helper::CreateFolderRecursive($this->uploadPath);

			$fileName = $file->getName();
			$tmpName = $file->getTmpName();
			$uploadPath = $this->uploadPath . "\\" . $fileName;

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

	}
