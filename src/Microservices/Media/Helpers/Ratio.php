<?php
	namespace DigitalSplash\Media\Helpers;

	/**
	 * Image Manager Basic Usage: https://image.intervention.io/v2/usage/overview#basic-usage
	 */
	use Intervention\Image\ImageManager;


	class Ratio {
		private string $source;
		private float $ratio;
		private string $destination;
		private bool $addCanvas;
		private bool $white;
		private int $width;
		private int $height;

		//https://stackoverflow.com/questions/44350072/add-white-space-to-image-using-laravel-5-intervention-image-to-make-square-image

		//Add "destination" string: default empty
		//Add "addCanvas" boolean: default false

		//If destination is not given, set it to be same as source.
			//Check if destination does not exist, then we need to create an empty image...

		//If canvas is given, we should add white borders to the top/bot or right/left

		public function __construct(
			string $source,
			float $ratio = 0,
			string $destination = '',
			bool $addCanvas = false,
			bool $white = true,
		) {
			$this->source = $source;
			$this->ratio = $ratio;
			$this->destination = $destination;
			$this->addCanvas = $addCanvas;
			$this->white = $white;
		}

		private function calculateDimensions(float $width, float $height): void {

			$ratio = $width / $height;

			if ($ratio === $this->ratio) {
				return;
			}

			if ($ratio > $this->ratio) {
				$targetHeight = $width / $this->ratio;
				$this->height = $height + 2 * (($targetHeight - $height) / 2);
				$this->width = $width;
			} else  {
				$targetWidth = $height * $this->ratio;
				$this->width = $width + 2 * (($targetWidth - $width) / 2);
				$this->height = $height;
			}
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
				$targetHeight = $width / $this->ratio;
				$newHeight = $height + 2 * (($targetHeight - $height) / 2);
				$newWidth = $width;
			} else  {
				$targetWidth = $height * $this->ratio;
				$newWidth = $width + 2 * (($targetWidth - $width) / 2);
				$newHeight = $height;
			}


			$image->resizeCanvas($newWidth, $newHeight, 'center', false);
			$image->save($this->source);
		}
	}
