<?php
	namespace DigitalSplash\Media\Helpers;

	/**
	 * Image Manager Basic Usage: https://image.intervention.io/v2/usage/overview#basic-usage
	 */

	use DigitalSplash\Helpers\Helper;
	use Intervention\Image\ImageManager;

	class Resize {

		private string $source;
		private string $destination;
		private int $width;

		public function __construct(
			string $source,
			string $destination,
			int $width
		) {
			$this->source = $source;
			$this->destination = $destination;
			$this->width = $width;
		}

		public function resize(): void {
			$manager = new ImageManager([
				'driver' => 'gd'
			]);
			$image = $manager->make($this->source);
			$image->resize($this->width, null, function ($constraint) {
				$constraint->aspectRatio();
			});

			Helper::CreateFolderRecursive(pathinfo($this->destination, PATHINFO_DIRNAME));
			$image->save($this->destination);
		}
	}