<?php
	namespace DigitalSplash\Media\Helpers;

	/**
	 * Image Manager Basic Usage: https://image.intervention.io/v2/usage/overview#basic-usage
	 */
	use Intervention\Image\ImageManager;


	class Ratio {
		private string $source;
		private float $ratio;

		//https://stackoverflow.com/questions/44350072/add-white-space-to-image-using-laravel-5-intervention-image-to-make-square-image

		//Add "destination" string: default empty
		//Add "addCanvas" boolean: default false

		//If destination is not given, set it to be same as source.
			//Check if destination does not exist, then we need to create an empty image...

		//If canvas is given, we should add white borders to the top/bot or right/left

		public function __construct(
			string $source,
			float $ratio = 0
		) {
			$this->source = $source;
			$this->ratio = $ratio;
		}

		public function Resize(): void {
			$manager = new ImageManager([
				'driver' => 'gd'
			]);
			$image = $manager->make($this->source);
			$width = $image->width();
			$height = $image->height();
			$ratio = $width / $height;

			if ($ratio === $this->ratio) {
				return;
			}

			if ($ratio > $this->ratio) {
				$newWidth = $height * $this->ratio;
				$newHeight = $height;
			} else  {
				$newWidth = $width;
				$newHeight = $width / $this->ratio;
			}

			$image->resizeCanvas($newWidth, $newHeight, 'center', false, '#ffffff');
			$image->save($this->source);
		}
	}
