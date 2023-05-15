<?php
	namespace DigitalSplash\Media\Helpers;

	use Intervention\Image\ImageManager;


	class Ratio {

		private string $source;
		private float $ratio;

		public function __construct(
			string $source,
			float $ratio = 0
		) {
			$this->source = $source;
			$this->ratio = $ratio;

			echo $source;
		}

		public function Resize(): void {
			$manager = new ImageManager(['driver' => 'gd']);
			$image = $manager->make($this->source);
			$width = $image->width();
			$height = $image->height();
			$ratio = $width / $height;

			if ($ratio > $this->ratio) {
				$newWidth = $height * $this->ratio;
				$newHeight = $height;
			} else {
				$newWidth = $width;
				$newHeight = $width / $this->ratio;
			}
			$image->resize($newWidth, $newHeight);
			$image->save(__DIR__ . '/../../../../_CommonFiles/Upload/resize.jpg');
		}
	}