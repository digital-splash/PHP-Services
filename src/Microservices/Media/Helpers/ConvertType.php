<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\UploadException;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use Intervention\Image\ImageManager;

	class ConvertType {
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

		public function convert(): void {
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
