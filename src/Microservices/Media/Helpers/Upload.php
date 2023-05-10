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

		private array $allowedExtensions;
		private string $uploadPath;
		private string $destinationFileName;

			// public $fileFullPath;
		// public $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		// public $convertToNextGen;
		// public $resize;
		// public $uploadedPaths;
		// public $uploadedData;

		public function __construct(
			array $phpFiles = [],
			string $destinationFileName = '',
			array $allowedExtensions = []
		) {
			$this->destinationFileName	= $destinationFileName;
			$this->allowedExtensions = $allowedExtensions;

			// $this->fileFullPath = "";
			// $this->ratio = 0;
			// $this->convertToNextGen = Upload::convertToNextGen;
			// $this->resize = true;
			// $this->uploadedPaths = [];
			// $this->uploadedData	= [];

			if (Helper::IsNullOrEmpty($this->allowedExtensions)) {
				$this->allowedExtensions = ImagesExtensions::getExtensions();
			}

			$this->uploadPath = Media::GetUploadDir();

			parent::__construct($phpFiles);

		}

		public function SetUploadPath(string $path): void {
			if (!Helper::StringEndsWith($path, ["/", "\\"])) {
				$path .= "/";
			}

			$this->uploadPath = $path;
		}

		public function upload(): array {
			$this->buildFiles();

			$retArr = [];

			$i = 1;
			foreach ($this->getFiles() as $file) {
				try {
					$retArr[] = $this->uploadFile($file, $i);

					$i++;
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

		private function uploadFile(File $file, int $i = 1): array {
			$file->validateFile($this->allowedExtensions);
			$uploadResponse = $this->uploadToServer($file, $i);

			return $uploadResponse;
		}

		private function uploadToServer(File $file, int $i = 1): array {
			Helper::CreateFolderRecursive($this->uploadPath);
			$extension = pathinfo($file->getName(), PATHINFO_EXTENSION);
			$destinationFileName = $this->destinationFileName;
			if (Helper::IsNullOrEmpty($destinationFileName)) {
				$destinationFileName = time() . '-' . rand(1000, 9999);
			}
			$destinationFileName = Helper::SafeName($destinationFileName . '-' . $i) . '.' . $extension;

			$uploadPath = $this->uploadPath . $destinationFileName;

			if (move_uploaded_file($file->getTmpName(), $uploadPath)) {
				return [
					'status' => Code::SUCCESS,
					'message' => 'upload.Success',
					'fileName' => $destinationFileName,
					'elemName' => $file->getElemName(),
				];
			} else {
				throw new UploadException();
			}
		}

	}
