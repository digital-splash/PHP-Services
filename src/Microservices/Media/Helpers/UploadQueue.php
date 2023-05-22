<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Models\File;
	use DigitalSplash\Media\Models\Image;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Models\Code;
	use Throwable;

	class UploadQueue extends Upload {

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
			$file->setUploadPath($this->uploadPath . '/' . $this->folders . '/original');
			return $this->uploadToServer($file, $i);
		}

		public function processImages(array $filesPath = []): array {
			$retArr = [];

			foreach ($filesPath as $filePath) {
				try {
					$retArr[] = $this->processImage($filePath);
				} catch (Throwable $t) {
					$retArr[] = [
						'status' => Code::ERROR,
						'message' => $t->getMessage(),
						'filePath' => $filePath,
					];
				}
			}

			return $retArr;
		}

		private function processImage(string $filePath): void {
			[
				'extension' => $extension,
				'basename' => $basename
			] = pathinfo($filePath);

			if (!Media::IsImage($extension)) {
				return;
			}

			$mainImagePath = Helper::RemoveMultipleSlashesInUrl($this->uploadPath . '/' . $this->folders . '/') . $basename;

			[
				'basename' => $basename,
				'filename' => $filename,
				'dirname' => $dirname
			] = pathinfo($mainImagePath);

			//copy to root
			copy($filePath, $mainImagePath);

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
				} catch (Throwable $t) {
					var_dump($t);
				}
			}

			//Check if we need to resize
			if ($this->resize) {
				try {
					foreach (Image::getArray() as $_image) {
						$destination = Helper::TextReplace($_image['path'], [
							'{path}' => $dirname
						]) . $basename;
						$resize = new Resize(
							$mainImagePath,
							$destination,
							$_image['width'],
							0,
							$this->convertToNextGen
						);
						$resize->save();
					}
				} catch (Throwable $t) {
					var_dump($t);
				}
			}

			if (count($this->facebookResize) > 0) {
				try {
					foreach ($this->facebookResize as $value) {
						$fbPath = Helper::TextReplace($value['path'], [
							'{path}' => $dirname
						]) . $basename;

						$resize = new Resize(
							$filePath,
							$fbPath,
							$value['width'],
							$value['ratio'],
							$this->convertToNextGen
						);
						$resize->save();
					}
				} catch (Throwable $t) {
					var_dump($t);
				}
			}

			//Check if we need to convert to next gen (webp)
			if ($this->convertToNextGen) {
				try {
					$convertType = new ConvertType(
						$mainImagePath,
						Helper::RemoveMultipleSlashesInUrl($dirname . '/' . $filename . '.' . ImagesExtensions::WEBP),
						ImagesExtensions::WEBP
					);
					$convertType->save();
				} catch (Throwable $t) {
					var_dump($t);
				}

			}

		}

	}
