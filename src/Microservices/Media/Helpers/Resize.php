<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\InvalidParamException;
	use DigitalSplash\Exceptions\UploadException;
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

		public function validateParams(): void {
			$validate = Helper::MissingNotEmptyParams([
				'source' => $this->source,
				'destination' => $this->destination,
			], [
				'source',
				'destination',
			]);

			if (!Helper::IsNullOrEmpty($validate['missing'])) {
				$error = Helper::ImplodeArrToStr(', ', $validate['missing']);
				if ($this->width <= 0) {
					$error .= (Helper::IsNullOrEmpty($error) ? '' : ', ') . 'width';
				}
				throw new InvalidParamException($error);
			}
			if ($this->width <= 0) {
				throw new InvalidParamException('width');
			}
			$this->validateSourceFileExists();
		}

		private function validateSourceFileExists(): void {
			if (!file_exists($this->source)) {
				throw new UploadException("Source file does not exist!");
			}
		}

		public function save(): void {
			$this->validateParams();

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
