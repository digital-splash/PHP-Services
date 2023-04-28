<?php
	namespace DigitalSplash\Media\Helpers;

	use DigitalSplash\Media\Models\DocumentsExtensions;
	use DigitalSplash\Media\Models\ImagesExtensions;

	class Upload{
		public const convertToNextGen = false;
		public const Error = 0;
		public const Success = 1;

		// public $_name;
		// public $_type;
		// public $_tmp_name;
		// public $_error;
		// public $_size;

		// public $fileFullPath;

		// public $elemName; //name attr of the file element
		// public $uploadPath; //upload path
		// public $folders; //folders inside the upload folder
		// public $destName; //destination filename
		// public $allowedExtensions; //allowed extensions
		// public $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		// public $convertToNextGen;
		// public $resize;
		// public $retArr;
		// public $uploadedPaths;
		// public $uploadedData;
		// public $successArr;
		// public $errorArr;
		// public $error;
		// public $isTest;

		public function __construct() {
			// $this->_name = "";
			// $this->_type = "";
			// $this->_tmp_name = "";
			// $this->_error = 0;
			// $this->_size = 0;
			// $this->fileFullPath = "";
			// $this->elemName	 = "";
			// $this->uploadPath = "";
			// $this->folders = "";
			// $this->destName	= "";
			// $this->allowedExtensions = ImagesExtensions::getExtensions();
			// $this->ratio = 0;
			// $this->convertToNextGen = Upload::convertToNextGen;
			// $this->resize = true;
			// $this->retArr = [];
			// $this->uploadedPaths = [];
			// $this->uploadedData	= [];
			// $this->successArr = [];
			// $this->errorArr = [];
			// $this->error = 0;
			// $this->isTest = false;
		}

		public static function uploadToServer($tmpName="", $uploadPath="", $fileName=""): array {
			$uploadedFileName	= pathinfo($uploadPath, PATHINFO_BASENAME);

			if (move_uploaded_file($tmpName, $uploadPath)) {
				return [
					"status"	=> self::Success,
					"message"	=> "File successfully uploaded!",
					"fileName"	=> $uploadedFileName
				];
			}else {
				return [
					"status"	=> self::Error,
					"message"	=> "Error while uploading file!",
					"fileName"	=> $uploadedFileName
				];
			}
		}

		public static function safeName($str=""): string {
			return preg_replace("/[-]+/", "-", preg_replace("/[^a-z0-9-]/", "", strtolower(str_replace(" ", "-", $str)))) ;
		}

		public static function CheckExtensionValidity($files, $allowedExtensionsArr=array()) {
			foreach ($files AS $elemName) {
				$fileName	= $_FILES[$elemName]["name"] ;
				$extName	= strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

				if ($fileName != "" && !in_array($extName, $allowedExtensionsArr)) {
					return $fileName;
				}
			}

			return true;
		}
	}