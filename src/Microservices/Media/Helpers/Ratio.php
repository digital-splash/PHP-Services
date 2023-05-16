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
		private bool $color;
		private int $width;
		private int $height;
		private string $extension;

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
			string $color = null,
		) {
			$this->source = $source;
			$this->ratio = $ratio;
			$this->destination = $destination;
			$this->addCanvas = $addCanvas;
			$this->color = $color;
			$this->extension = strtolower(pathinfo($this->source, PATHINFO_EXTENSION));
		}

		private function colorSetter(): void {
			switch ($this->extension) {
				case 'png':
					$this->color = $this->color ?? null;
					break;
				default:
					$this->color = $this->color ?? '#ffffff';
					break;
			}
		}

		private function calculateDimensionsWithCanvas(float $width, float $height): void {

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

			$this->colorSetter();
		}

		private function calculateDimensionsWithoutCanvas(float $width, float $height): void {
			$ratio = $width / $height;

			if ($ratio === $this->ratio) {
				return;
			}

			if ($ratio > $this->ratio) {
				$this->height = $width / $this->ratio;
				$this->width = $width;
			} else  {
				$this->width = $height * $this->ratio;
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

			if ($this->addCanvas) {
				$this->calculateDimensionsWithCanvas($width, $height);
				$image->resizeCanvas($this->width, $this->height, 'center', false, $this->color);
			} else {
				$this->calculateDimensionsWithoutCanvas($width, $height);
				$image->resize($this->width, $this->height);
			}

			$image->save($this->destination);
		}
	}
