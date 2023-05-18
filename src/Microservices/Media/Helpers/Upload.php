<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\UploadException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Media\Models\File;
	use DigitalSplash\Media\Models\Files;
	use DigitalSplash\Media\Models\Image;
	use DigitalSplash\Models\Code;
	use Throwable;

	class Upload extends Files {

		public const convertToNextGen = false;

		private array $allowedExtensions;
		private string $uploadPath;
		private string $folders;
		private string $destinationFileName;
		private float $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		private bool $convertToNextGen;

		// public $fileFullPath;
		// public $resize;
		// public $uploadedPaths;
		// public $uploadedData;

		public function __construct(
			array $phpFiles = [],
			string $destinationFileName = '',
			string $folders = '',
			array $allowedExtensions = [],
			float $ratio = 0,
			bool $convertToNextGen = false
		) {
			$this->destinationFileName = $destinationFileName;
			$this->folders = Helper::RemoveMultipleSlashesInUrl($folders);
			$this->allowedExtensions = $allowedExtensions;
			$this->ratio = $ratio;
			$this->convertToNextGen = $convertToNextGen;

			// $this->fileFullPath = "";
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

			$this->uploadPath = Helper::RemoveMultipleSlashesInUrl($path);
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
			$file->setUploadPath(Helper::RemoveMultipleSlashesInUrl($this->uploadPath . '/' . $this->folders));
			$uploadResponse = $this->uploadToServer($file, $i);

			if ($file->IsImage()) {
				$mainImagePath = $uploadResponse['uploadedFile'];
				$this->createOriginalFile($file, $uploadResponse);

				//Check if we need to change ratio
				if ($this->ratio != 0) {
					try {
						$ratio = new Ratio(
							$mainImagePath,
							$this->ratio,
							$mainImagePath,
							true
						);
						$ratio->Resize();
					} catch (Throwable $t) {}
				}

				//Check if we need to convert to next gen (webp)
				if ($this->convertToNextGen) {
					try {
						$convertType = new ConvertType(
							$mainImagePath,
							Helper::RemoveMultipleSlashesInUrl($this->uploadPath . '/' . $this->folders . '/' . pathinfo($uploadResponse['fileName'], PATHINFO_FILENAME) . '.webp')
						);
						$convertType->convert();
					} catch (Throwable $t) {}
				}

				//Check if we need to resize
					//If yes, resize to all defined sizes

				//Resize to Facebook Ratio
			}

			return $uploadResponse;
		}

		private function uploadToServer(File $file, int $i = 1): array {
			Helper::CreateFolderRecursive($file->getUploadPath());

			$destinationFileName = $this->destinationFileName;
			if (Helper::IsNullOrEmpty($destinationFileName)) {
				$destinationFileName = time() . '-' . rand(1000, 9999);
			}
			$destinationFileName = Helper::SafeName($destinationFileName . '-' . $i) . '.' . $file->getExtension();

			$uploadFileDest = Helper::RemoveMultipleSlashesInUrl($file->getUploadPath() . '/' . $destinationFileName);

			if (move_uploaded_file($file->getTmpName(), $uploadFileDest)) {
				$mediaPath = Helper::RemoveMultipleSlashesInUrl($this->folders . '/' . $destinationFileName);
				if (Helper::StringBeginsWith($mediaPath, ["/", "\\"])) {
					$mediaPath = substr($mediaPath, 1, strlen($mediaPath) - 1);
				}

				return [
					'status' => Code::SUCCESS,
					'message' => 'upload.Success',
					'elemName' => $file->getElemName(),
					'mediaPath' => $mediaPath,
					'fileName' => $destinationFileName,
					'uploadedFile' => $uploadFileDest
				];
			} else {
				throw new UploadException();
			}
		}

		private function createOriginalFile(File $file, array $fileToCopy): void {
			$originalUploadPath = Helper::RemoveMultipleSlashesInUrl(Helper::TextReplace(Image::ORIGINAL_PATH, [
				'{path}' => $file->getUploadPath()
			]));
			Helper::CreateFolderRecursive($originalUploadPath);

			$originalFile = Helper::RemoveMultipleSlashesInUrl($originalUploadPath . '/' . $fileToCopy['fileName']);
			copy($fileToCopy['uploadedFile'], $originalFile);
		}

	}
