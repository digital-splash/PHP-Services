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
			$validate = Helper::MissingNotEmptyParams([
				'source' => $this->source,
				'destination' => $this->destination,
				'extension' => $this->extension,
			], [
				'source',
				'destination',
				'extension'
			]);

			if (!Helper::IsNullOrEmpty($validate['missing'])) {
				throw new InvalidParamException(Helper::ImplodeArrToStr(', ', $validate['missing']));
			}

			if (!file_exists($this->source)) {
				throw new UploadException("Source file does not exist!");
			}

			Media::validateIsImage($this->extension);
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
			Helper::CreateFolderRecursive(pathinfo($this->destination, PATHINFO_DIRNAME));
			$image->encode($this->extension, 90)->save($this->destination);

			if (!$this->keepSource) {
				unlink($this->source);
			}
		}
	}
