<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Interface\IImageModify;
	use Intervention\Image\ImageManager;

	class Resize implements IImageModify {

		private string $source;
		private string $destination;
		private int $width;
		private float $ratio;

		public function __construct(
			string $source,
			string $destination,
			int $width,
			float $ratio = 0
		) {
			$this->source = $source;
			$this->destination = $destination;
			$this->width = $width;
			$this->ratio = $ratio;
		}

		public function save(): void {
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
