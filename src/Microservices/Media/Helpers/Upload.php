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
		private float $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one

			// public $fileFullPath;
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
			$this->ratio = 0;
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
		}

		private function uploadFile(File $file, int $i = 1): array {
			$file->validateFile($this->allowedExtensions);
			$uploadResponse = $this->uploadToServer($file, $i);

			if ($file->IsImage()) {
				//Check if we need to change ratio
				if ($this->ratio != 0) {
					//Copy to original folder
					copy($uploadResponse['newPath'], __DIR__ . '/../../../tests/_CommonFiles/original');
					//Change image ratio and save to upload folder
					$this->changeImageRatio($file);
				}

				//Check if we need to convert to next gen (webp)
					//If yes, convert

				//Check if we need to resize
					//If yes, resize to all defined sizes

				//Resize to Facebook Ratio
			}

			return $uploadResponse;
		}

		private function uploadToServer(File $file, int $i = 1): array {
			Helper::CreateFolderRecursive($this->uploadPath);
			$extension = $file->getExtension();
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
					'newPath' => $uploadPath
				];
			} else {
				throw new UploadException();
			}
		}

		private function changeImageRatio(File $file): void {
			//Get image size
			list($srcImgWidth, $srcImgHeight, $srcImgType) = getimagesize($file->getTmpName());

			$originalRatio = $srcImgWidth / $srcImgHeight;

			if( $this->ratio != $originalRatio) {
				//Get new image size
				$newWidth = $srcImgWidth;
				$newHeight = $srcImgHeight;

				if ($this->ratio > $originalRatio) {
					$newHeight = $newWidth / $this->ratio;
				} else {
					$newWidth = $newHeight * $this->ratio;
				}

				switch ($srcImgType) {
					case IMAGETYPE_GIF:
						$source = imagecreatefromgif($file->getTmpName());
						break;
					case IMAGETYPE_JPEG:
						$source = imagecreatefromjpeg($file->getTmpName());
						break;
					case IMAGETYPE_PNG:
						$source = imagecreatefrompng($file->getTmpName());
						break;
					default:
						throw new UploadException('Type not supported');
				}

				//Create new image
				$newImage = imagecreatetruecolor($newWidth, $newHeight);
				$source = imagecreatefromjpeg($file->getTmpName());

				//Resize
				imagecopyresized($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $srcImgWidth, $srcImgHeight);

				//Save
				imagejpeg($newImage, $file->getTmpName());

				//Free memory
				imagedestroy($source);
				imagedestroy($newImage);
				$this->uploadPath = __DIR__ . '/../../../tests/_CommonFiles/original';
				$this->uploadToServer(
					$file,
					1
				);
			}
		}

	}
