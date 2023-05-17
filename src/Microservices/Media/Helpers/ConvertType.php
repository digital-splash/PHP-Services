<?php
	namespace DigitalSplash\Media\Helpers;

	/**
	 * Image Manager Basic Usage: https://image.intervention.io/v2/usage/overview#basic-usage
	 */

	use DigitalSplash\Helpers\Helper;
	use Intervention\Image\ImageManager;

    class ConvertType {

        private $source;
        private $destination;

        public function __construct(
            string $source,
            string $destination
        ) {
            $this->source = $source;
            $this->destination = $destination;
        }

        public function convert(): void {
            $manager = new ImageManager([
				'driver' => 'gd'
			]);
            $image = $manager->make($this->source);
            unlink($this->source);
            $image->encode('webp', 90)->save($this->destination);
        }
    }