<?php
	namespace RawadyMario\Media\Helpers;

	use RawadyMario\Exceptions\InvalidParamException;
	use RawadyMario\Exceptions\NotEmptyParamException;
	use RawadyMario\Exceptions\UploadException;
	use RawadyMario\Helpers\Helper;
	use RawadyMario\Models\Status;
	use Throwable;

	class Upload {
		// public const Error		= 0;
		// public const Success	= 1;
		public const IMG_EXTENSIONS	= ["jpg", "jpeg", "png"];
		public const DOC_EXTENSIONS	= ["pdf", "doc", "docx", "xls", "xlsx"];

		private string $_name;
		private string $_type;
		private string $_tmp_name;
		private int $_error;
		private int $_size;

		// public $fileFullPath;

		protected string $elemName; //name attr of the file element
		protected string $uploadPath; //upload path
		protected string $folders; //folders inside the upload folder
		protected string $destName; //destination filename

		protected float $ratio; //ratio. If not equal to 0, then force change the image ratio to the given one
		protected bool $convertToNextGen;
		protected bool $resize;

		protected array $allowedExtensions; //allowed extensions
		protected array $retArr;
		// public $uploadedPaths;
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

			// $this->fileFullPath			= "";

			$this->elemName = "";
			$this->uploadPath = "";
			$this->folders = "";
			$this->destName = "";

			$this->ratio = 0;
			$this->convertToNextGen = false;
			$this->resize = false;

			$this->allowedExtensions = self::IMG_EXTENSIONS;
			$this->retArr = [];
			// $this->uploadedPaths		= [];
			// $this->successArr			= [];
			// $this->errorArr				= [];
			// $this->error				= 0;

			// $this->isTest				= false;
		}


		public function Upload(): void {
			try {
				$this->ValidateGivenValues();
			}
			catch (Throwable $e) {
				$this->AddToResponse(
					Status::ERROR,
					$e->getMessage()
				);
				return;
			}

			if (!is_array($_FILES[$this->elemName]["name"])) {
				$this->_name = $_FILES[$this->elemName]["name"];
				$this->_type = $_FILES[$this->elemName]["type"];
				$this->_tmp_name = $_FILES[$this->elemName]["tmp_name"];
				$this->_error = $_FILES[$this->elemName]["error"];
				$this->_size = $_FILES[$this->elemName]["size"];

				try {
					$this->UploadFile();
				}
				catch (Throwable $e) {
					$this->AddToResponse(
						Status::ERROR,
						$e->getMessage()
					);
				}
			}
			else if (is_array($_FILES[$this->elemName]["name"])) {
				for ($i = 0; $i < count($_FILES[$this->elemName]["name"]); $i++) {
					$this->_name = $_FILES[$this->elemName]["name"][$i];
					$this->_type = $_FILES[$this->elemName]["type"][$i];
					$this->_tmp_name = $_FILES[$this->elemName]["tmp_name"][$i];
					$this->_error = $_FILES[$this->elemName]["error"][$i];
					$this->_size = $_FILES[$this->elemName]["size"][$i];

					try {
						$this->UploadFile();
					}
					catch (Throwable $e) {
						$this->AddToResponse(
							Status::ERROR,
							$e->getMessage()
						);
					}

					// $this->destName = "";
				}
			}

			// $this->fixRetArr();
		}

		protected function UploadFile(): void {
			$this->HandleUploadFileError();

			if (!is_uploaded_file($this->_tmp_name)) {
				Throw new UploadException("upload.Error.FileNotUploaded");
			}

			$extension = pathinfo($this->_name, PATHINFO_EXTENSION);
			if (!in_array($extension, $this->allowedExtensions)) {
				$allowed = implode(", ", $this->allowedExtensions);
				Throw new UploadException("upload.Error.InvalidFormat", [
					"::allowed::" => $allowed
				]);
			}

			$uploadFolder = str_replace("//", "/", $this->uploadPath . "/" . $this->folders . "/");
			Helper::CreateFolderRecursive($uploadFolder);

			if (Helper::StringNullOrEmpty($this->destName)) {
				$this->destName = time() . " " . rand(1000, 9999);
			}
			$this->destName = Helper::SafeName($this->destName) . "." . $extension;

			$uploadPath = $uploadFolder . $this->destName;
			// $originalPath		= $uploadFolder . "original/" . $this->destName;

			// $this->uploadedPaths[] = $this->folders . $this->destName;

			if (in_array($extension, self::IMG_EXTENSIONS)) {
				//Upload Original Image
				// self::uploadToServer($_tmp_name, $originalPath, $this->destName);

				// $this->retArr[] = self::changeImgRatio($this->ratio, $uploadPath, $originalPath);

				// if ($this->convertToNextGen) {
				// 	$this->retArr[] = self::convertImgToNextGen($uploadPath, "webp", false);
				// }

				// if ($this->resize) {
				// 	$imgResizeOptions = [
				// 		"hd",
				// 		"ld",
				// 		"th",
				// 	];
				// 	foreach ($imgResizeOptions AS $resizeOption) {
				// 		$resizeRet = self::resizeImg($uploadPath, $resizeOption, $this->convertToNextGen);
				// 		$this->retArr[] = $resizeRet;
				// 	}
				// }
				// $this->retArr[] = self::generateFacebookImg($originalPath, true);
			}
			else {
				// self::deleteFile($uploadPath);
				// $this->retArr[] = self::uploadToServer($_tmp_name, $uploadPath, $this->_name);
			}

			var_dump("Uploaaaaad!");
			exit;
		}

		protected function ValidateGivenValues(): void {
			if (Helper::StringNullOrEmpty($this->elemName)) {
				throw new NotEmptyParamException("elemName");
			}

			if (Helper::StringNullOrEmpty($this->uploadPath)) {
				throw new NotEmptyParamException("uploadPath");
			}

			if (Helper::ArrayNullOrEmpty($this->allowedExtensions)) {
				throw new NotEmptyParamException("allowedExtensions");
			}

			if (!isset($_FILES[$this->elemName])) {
				throw new InvalidParamException("\$_FILES['" . $this->elemName . "']");
			}
		}

		protected function HandleUploadFileError(): void {
			switch ($this->_error) {
				case UPLOAD_ERR_OK:
					return;

				case UPLOAD_ERR_INI_SIZE:
					Throw new UploadException("upload.Error.IniSize");

				case UPLOAD_ERR_FORM_SIZE:
					$maxSize	= $_POST["MAX_FILE_SIZE"];
					$maxSizeKb	= round($maxSize / 1024);

					Throw new UploadException("upload.Error.FormSize", [
						"::MaxSizeKb::" => $maxSizeKb
					]);

				case UPLOAD_ERR_PARTIAL:
					Throw new UploadException("upload.Error.PartiallyUploaded");

				case UPLOAD_ERR_NO_FILE:
					Throw new UploadException("upload.Error.NoFile");

				case UPLOAD_ERR_NO_TMP_DIR:
					Throw new UploadException("upload.Error.NoTempDir");

				case UPLOAD_ERR_CANT_WRITE:
					Throw new UploadException("upload.Error.CantWrite");

				case UPLOAD_ERR_EXTENSION:
					Throw new UploadException("upload.Error.Extension");

				default:
					Throw new UploadException("upload.Error.Unknown");
			}
		}

		protected function AddToResponse(
			string $status,
			string $message
		) {
			$this->retArr[] = [
				"status"	=> $status,
				"message"	=> $message,
				"elemName"	=> $this->elemName,
				"fileName"	=> $this->_name
			];
		}

		protected static function UploadToServer(
			string $tmpName,
			string $uploadPath
		) {
			$uploadedFileName	= pathinfo($uploadPath, PATHINFO_BASENAME);

			if (move_uploaded_file($tmpName, $uploadPath)) {
				return [
					"status" => Status::SUCCESS,
					"message" => "upload.Success"
				];
			}
			else {
				return [
					"status" => Status::ERROR,
					"message" => "upload.Error.WhileUploading"
				];
			}
		}

		public function SetElemName(string $var): void {
			$this->elemName = $var;
		}

		public function GetElemName(): string {
			return $this->elemName;
		}

		public function SetUploadPath(string $var): void {
			$this->uploadPath = $var;
		}

		public function GetUploadPath(): string {
			return $this->uploadPath;
		}

		public function SetFolders(string $var): void {
			$this->folders = $var;
		}

		public function GetFolders(): string {
			return $this->folders;
		}

		public function SetDestName(string $var): void {
			$this->destName = $var;
		}

		public function GetDestName(): string {
			return $this->destName;
		}

		public function SetRatio(float $var): void {
			$this->ratio = $var;
		}

		public function GetRatio(): float {
			return $this->ratio;
		}

		public function SetConvertToNextGen(bool $var): void {
			$this->convertToNextGen = $var;
		}

		public function GetConvertToNextGen(): bool {
			return $this->convertToNextGen;
		}

		public function SetResize(bool $var): void {
			$this->resize = $var;
		}

		public function GetResize(): bool {
			return $this->resize;
		}

		public function SetAllowedExtensions(array $var): void {
			$this->allowedExtensions = $var;
		}

		public function GetAllowedExtensions(): array {
			return $this->allowedExtensions;
		}

		public function GetUploadResponse(): array {
			return $this->retArr;
		}

	}
