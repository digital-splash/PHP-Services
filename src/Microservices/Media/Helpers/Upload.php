<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Exceptions\UploadException;
	use DigitalSplash\Media\Models\DocumentsExtensions;
	use DigitalSplash\Media\Models\ImagesExtensions;

	class Upload{
		public const convertToNextGen = false;
		public const Error = 0;
		public const Success = 1;

		private $_name;
		private $_type;
		private $_tmp_name;
		private $_error;
		private $_size;
		private $_files;

		// public $fileFullPath;

		// public $elemName; //name attr of the file element
		// public $uploadPath; //upload path
		// public $folders; //folders inside the upload folder
		// public $destName; //destination filename
		private $allowedExtensions; //allowed extensions
		// public $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		// public $convertToNextGen;
		// public $resize;
		private $retArr;
		// public $uploadedPaths;
		// public $uploadedData;
		// public $successArr;
		// public $errorArr;
		// public $error;
		// public $isTest;

		public function __construct() {
			$this->_name = "";
			$this->_type = "";
			$this->_tmp_name = "";
			$this->_error = 0;
			$this->_size = 0;
			// $this->fileFullPath = "";
			// $this->elemName	 = "";
			// $this->uploadPath = "";
			// $this->folders = "";
			// $this->destName	= "";
			$this->allowedExtensions = ImagesExtensions::getExtensions();
			// $this->ratio = 0;
			// $this->convertToNextGen = Upload::convertToNextGen;
			// $this->resize = true;
			$this->retArr = [];
			// $this->uploadedPaths = [];
			// $this->uploadedData	= [];
			// $this->successArr = [];
			// $this->errorArr = [];
			// $this->error = 0;
			// $this->isTest = false;
		}

		public function setName(string $name): void {
			$this->_name = $name;
		}

		public function getName(): string {
			return $this->_name;
		}

		public function setType(string $type): void {
			$this->_type = $type;
		}

		public function getType(): string {
			return $this->_type;
		}

		public function setTmpName(string $tmpName): void {
			$this->_tmp_name = $tmpName;
		}

		public function getTmpName(): string {
			return $this->_tmp_name;
		}

		public function setError(int $error): void {
			$this->_error = $error;
		}

		public function getError(): int {
			return $this->_error;
		}

		public function setSize(int $size): void {
			$this->_size = $size;
		}

		public function getSize(): int {
			return $this->_size;
		}

		public function setFiles(array $files): void {
			$this->_files = $files;
		}

		public function getFiles(): array {
			return $this->_files;
		}

		

		public static function safeName($str=""): string {
			return preg_replace("/[-]+/", "-", preg_replace("/[^a-z0-9-]/", "", strtolower(str_replace(" ", "-", $str)))) ;
		}

		public static function CheckExtensionValidity($file, $allowedExtensionsArr=array()) {
				$fileName	= $file['name'];
				$extName	= strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

				if ($fileName != "" && !in_array($extName, $allowedExtensionsArr)) {
					return $fileName;
				}

			return true;
		}
	}