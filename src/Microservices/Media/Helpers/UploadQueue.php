<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Models\File;
	use DigitalSplash\Media\Models\Image;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Models\Code;
	use Throwable;

	class UploadQueue extends Upload {

		private array $queue;

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

			$this->queue [] = $uploadResponse['uploadedFile'];

			return $uploadResponse;
		}

		public function ProcessImages(): array {
			$retArr = [];
			foreach ($this->queue as $file) {
				try {
					$retArr[] = $this->processImage($file);
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

		private function processImage(string $filePath): void {
			//copy to root
			copy($filePath, $this->uploadPath . '/' . $this->folders . '/' . pathinfo($filePath, PATHINFO_BASENAME));
			$mainImagePath = $this->uploadPath . '/' . $this->folders . '/' . pathinfo($filePath, PATHINFO_BASENAME);
			//Check if we need to change ratio
			if ($this->ratio != 0) {
				try {
					$ratio = new Ratio(
						$mainImagePath,
						$this->ratio,
						$mainImagePath,
						true
					);
					$ratio->save();
				} catch (Throwable $t) {}
			}

			//Check if we need to resize
			if ($this->resize) {
				try {
					foreach (Image::getArray() as $_image) {
						$destination = Helper::TextReplace($_image['path'], [
							'{path}' => pathinfo($mainImagePath, PATHINFO_DIRNAME)
						]) . pathinfo($filePath, PATHINFO_BASENAME);
						$resize = new Resize(
							$mainImagePath,
							$destination,
							$_image['width'],
							0,
							$this->convertToNextGen
						);
						$resize->save();
					}
				} catch (Throwable $t) {}
			}

			if (count($this->facebookResize) > 0) {
				try {
					foreach ($this->facebookResize as $key => $value) {
						$fbPath = Helper::TextReplace($value['path'], [
							'{path}' => pathinfo($mainImagePath, PATHINFO_DIRNAME)
						]) . pathinfo($filePath, PATHINFO_BASENAME);

						$resize = new Resize(
							$filePath,
							$fbPath,
							$value['width'],
							$value['ratio'],
							$this->convertToNextGen
						);
						$resize->save();
					}
				} catch (Throwable $t) {}
			}

			//Check if we need to convert to next gen (webp)
			if ($this->convertToNextGen) {
				try {
					$convertType = new ConvertType(
						$mainImagePath,
						Helper::RemoveMultipleSlashesInUrl(pathinfo($mainImagePath, PATHINFO_DIRNAME) . '/' . pathinfo($mainImagePath, PATHINFO_FILENAME) . '.' . ImagesExtensions::WEBP),
						ImagesExtensions::WEBP
					);
					$convertType->save();
				} catch (Throwable $t) {}

			}

		}
		//constructor
		//only uploads to the original folder
		//processimages (imagePath of orginal) -> copy to root and does all upload work
	}