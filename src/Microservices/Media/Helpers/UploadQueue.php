<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Media\Models\File;
	use DigitalSplash\Media\Models\Files;
	use DigitalSplash\Models\Code;
	use Throwable;

	class UploadQueue extends Upload {

		public function __construct (
			array $phpFiles = [],
			string $destinationFileName = '',
			string $folders = '',
			array $allowedExtensions = [],
			float $ratio = 0,
			bool $convertToNextGen = false,
			bool $resize = false,
			array $facebookResize = []
		) {
			parent::__construct(
				$phpFiles,
				$destinationFileName,
				$folders,
				$allowedExtensions,
				$ratio,
				$convertToNextGen,
				$resize,
				$facebookResize
			);
		}

		public function UploadToOriginal(): array {
			$this->buildFiles();
			$retArr = [];

			$i = 1;
			foreach ($this->getFiles() as $file) {
			   try {
				 $retArr[] = $this->uploadFileToOriginal($file, $i);
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

		private function uploadFileToOriginal(File $file, int $i = 1): array {
			$file->validateFile($this->allowedExtensions);
			$file->setUploadPath($this->uploadPath . '/' . $this->folders . '/original');
			$uploadResponse = $this->uploadToServer($file, $i);

			return $uploadResponse;
		}
		//constructor
		//only uploads to the original folder
		//processimages (imagePath of orginal) -> copy to root and does all upload work


	}