<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\UploadException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Models\FacebookImage;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use DigitalSplash\Media\Models\File;
	use DigitalSplash\Media\Models\Files;
	use DigitalSplash\Media\Models\Image;
	use DigitalSplash\Models\Code;
	use Throwable;

	class Upload extends Files {

		private const FILE_ELEMENT_KEYS_TO_REPLACE = [
			'mediaPath', 'fileName', 'extension', 'uploadedFile'
		];

		private array $allowedExtensions;
		private string $uploadPath;
		private string $folders;
		private string $destinationFileName;
		private float $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		private bool $convertToNextGen;

		private bool $resize;
		private array $imageResize = [
			'thumbnail' => [
				'width' => Image::THUMBNAIL_WIDTH,
				'code' => Image::THUMBNAIL_CODE,
				'path' => Image::THUMBNAIL_PATH
			],
			'lowDef' => [
				'width' => Image::LOW_DEF_WIDTH,
				'code' => Image::LOW_DEF_CODE,
				'path' => Image::LOW_DEF_PATH
			],
			'highDef' => [
				'width' => Image::HIGH_DEF_WIDTH,
				'code' => Image::HIGH_DEF_CODE,
				'path' => Image::HIGH_DEF_PATH
			]
		];
		// private array $facebookResize = [
		// 	'profile' => [
		// 		'width' => FacebookImage::PROFILE_WIDTH,
		// 		'ratio' => FacebookImage::PROFILE_RATIO,
		// 		'path' => FacebookImage::PROFILE_PATH
		// 	],
		// 	'cover' => [
		// 		'width' => FacebookImage::COVER_WIDTH,
		// 		'ratio' => FacebookImage::COVER_RATIO,
		// 		'path' => FacebookImage::COVER_PATH
		// 	],
		// 	'post' => [
		// 		'width' => FacebookImage::POST_WIDTH,
		// 		'ratio' => FacebookImage::POST_RATIO,
		// 		'path' => FacebookImage::POST_PATH
		// 	]
		// ];

		public function __construct(
			array $phpFiles = [],
			string $destinationFileName = '',
			string $folders = '',
			array $allowedExtensions = [],
			float $ratio = 0,
			bool $convertToNextGen = false,
			bool $resize = false
		) {
			$this->destinationFileName = $destinationFileName;
			$this->folders = Helper::RemoveMultipleSlashesInUrl($folders);
			$this->allowedExtensions = $allowedExtensions;
			$this->ratio = $ratio;
			$this->convertToNextGen = $convertToNextGen;
			$this->resize = $resize;

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
			$file->setUploadPath($this->uploadPath . '/' . $this->folders);
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
						$ratio->save();
					} catch (Throwable $t) {}
				}

				//Check if we need to convert to next gen (webp)
				if ($this->convertToNextGen) {
					try {
						$convertType = new ConvertType(
							$mainImagePath,
							Helper::RemoveMultipleSlashesInUrl($uploadResponse['uploadFolder'] . '/' . $uploadResponse['fileNameWithoutExtension'] . '.' . ImagesExtensions::WEBP),
							ImagesExtensions::WEBP
						);
						$convertType->convert();

						$oldExtension = $uploadResponse['extension'];
						foreach ($uploadResponse as $key => &$uploadItem) {
							if (in_array($key, self::FILE_ELEMENT_KEYS_TO_REPLACE) && Helper::StringEndsWith($uploadItem, $oldExtension)) {
								$uploadItem = Helper::TruncateStr($uploadItem, strlen($uploadItem) - strlen($oldExtension), 'webp', '', false);
							}
						}
					} catch (Throwable $t) {}
				}

				//Check if we need to resize
				if ($this->resize) {
					try {
						foreach ($this->imageResize as $key => $value) {
							$resize = new Resize(
								$mainImagePath,
								Helper::TextReplace($value['path'], [
									'{path}' => $file->getUploadPath()
								]) . pathinfo($uploadResponse['fileName'], PATHINFO_FILENAME) . '_' . $value['code'] . '.' . pathinfo($uploadResponse['fileName'], PATHINFO_EXTENSION),
								$value['width']
							);
							$resize->Resize();
						}
					} catch (Throwable $t) {}
				}
				// if ($this->resize) {
				// 	try {
				// 		foreach ($this->facebookResize as $key => $value) {
				// 			$fbPath = Helper::TextReplace($value['path'], [
				// 				'{path}' => $file->getUploadPath()
				// 			]) . $uploadResponse['fileName'];

				// 			$ratio = new Ratio(
				// 				$mainImagePath,
				// 				$value['ratio'],
				// 				$fbPath,
				// 				true
				// 			);
				// 			$ratio->save();
				// 			$resize = new Resize(
				// 				$fbPath,
				// 				$fbPath,
				// 				$value['width']
				// 			);
				// 			$resize->Resize();
				// 		}
				// 	} catch (Throwable $t) {}
				// }

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

				[
					'basename' => $basename,
					'extension' => $extension,
					'filename' => $filename,
				] = pathinfo($destinationFileName);

				return [
					'status' => Code::SUCCESS,
					'message' => 'upload.Success',
					'elemName' => $file->getElemName(),
					'mediaPath' => $mediaPath,
					'fileName' => $basename,
					'fileNameWithoutExtension' => $filename,
					'extension' => $extension,
					'uploadFolder' => Helper::RemoveMultipleSlashesInUrl($file->getUploadPath() . '/'),
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
