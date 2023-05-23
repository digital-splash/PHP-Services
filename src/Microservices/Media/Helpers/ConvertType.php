<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\InvalidParamException;
	use DigitalSplash\Exceptions\UploadException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Interface\IImageModify;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use Intervention\Image\ImageManager;

	class ConvertType implements IImageModify {
		private string $source;
		private string $destination;
		private string $extension;
		private bool $keepSource;

		public function __construct(
			string $source,
			string $destination,
			string $extension,
			bool $keepSource = false
		) {
			$this->source = $source;
			$this->destination = $destination;
			$this->extension = $extension;
			$this->keepSource = $keepSource;
		}

		public function validateParams(): void {
			if (Helper::IsNullOrEmpty($this->source) || Helper::IsNullOrEmpty($this->destination) || Helper::IsNullOrEmpty($this->extension)) {
				throw new InvalidParamException($this->buildParamExceptionMessage());
			}

			$this->validateSourceFileExists();
			$this->validateDestinationDirectoryExists();
			$this->validateExtensionAllowed();
		}

		private function buildParamExceptionMessage(): string {
			$exceptionMessage = '';

			if (Helper::IsNullOrEmpty($this->source)) {
				$exceptionMessage .= 'source';
			}

			if (Helper::IsNullOrEmpty($this->destination)) {
				$exceptionMessage .= (Helper::IsNullOrEmpty($exceptionMessage) ? '' : ', ') . 'destination';
			}

			if (Helper::IsNullOrEmpty($this->extension)) {
				$exceptionMessage .= (Helper::IsNullOrEmpty($exceptionMessage) ? '' : ', ') . 'extension';
			}

			return $exceptionMessage;
		}

		private function validateSourceFileExists(): void {
			if (!file_exists($this->source)) {
				throw new UploadException("Source file does not exist!");
			}
		}

		private function validateDestinationDirectoryExists(): void {
			$destinationDirectory = pathinfo($this->destination, PATHINFO_DIRNAME);

			if (!is_dir($destinationDirectory)) {
				throw new UploadException("Destination directory does not exist!");
			}
		}

		private function validateExtensionAllowed(): void {
			$allowedExtensions = ImagesExtensions::getExtensions();

			if (!in_array($this->extension, $allowedExtensions)) {
				$allowed = implode(", ", $allowedExtensions);
				throw new UploadException("File extension is not allowed! Allowed extensions: $allowed");
			}
		}

		public function save(): void {
			$this->validateParams();
			$allowedExtensions = ImagesExtensions::getExtensions();
			if (!in_array($this->extension, $allowedExtensions)) {
				$allowed = implode(", ", $allowedExtensions);
				throw new UploadException("File extension is not allowed! Allowed extensions: $allowed");
			}

			$manager = new ImageManager([
				'driver' => 'gd'
			]);
			$image = $manager->make($this->source);
			$image->encode($this->extension, 90)->save($this->destination);

			if (!$this->keepSource) {
				unlink($this->source);
			}
		}
	}
