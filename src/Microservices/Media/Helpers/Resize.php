<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Interface\IImageModify;
	use DigitalSplash\Media\Models\ImagesExtensions;
	use Intervention\Image\ImageManager;

	class Resize implements IImageModify {

		private string $source;
		private string $destination;
		private int $width;
		private float $ratio;
		private bool $convertToNextGen;

		public function __construct(
			string $source,
			string $destination,
			int $width,
			float $ratio = 0,
			bool $convertToNextGen = false
		) {
			$this->source = $source;
			$this->destination = $destination;
			$this->width = $width;
			$this->ratio = $ratio;
			$this->convertToNextGen = $convertToNextGen;
		}

		public function save(): void {

			if ($this->ratio != 0) {
				$changeRatio = new Ratio(
					$this->source,
					$this->ratio,
					$this->destination
				);
				$changeRatio->save();
				$this->source = $this->destination;
			}

			$manager = new ImageManager([
				'driver' => 'gd'
			]);

			$image = $manager->make($this->source);
			$image->resize($this->width, null, function ($constraint) {
				$constraint->aspectRatio();
			});

			Helper::CreateFolderRecursive(pathinfo($this->destination, PATHINFO_DIRNAME));
			$image->save($this->destination);

			if ($this->convertToNextGen) {
				$convertToNextGen = new ConvertType(
					$this->destination,
					pathinfo($this->destination, PATHINFO_DIRNAME) . '/' . pathinfo($this->destination, PATHINFO_FILENAME) . '.webp',
					ImagesExtensions::WEBP
				);
				$convertToNextGen->save();
			}

		}
	}
