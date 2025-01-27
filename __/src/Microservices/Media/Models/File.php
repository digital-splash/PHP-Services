<?php

	namespace DigitalSplash\Media\Models;

	use DigitalSplash\Exceptions\Media\UploadException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Helpers\Media;

	class File {
		private string $_elemName;
		private string $_name;
		private string $_type;
		private string $_tmpName;
		private string $uploadPath;
		private int $_error;
		private int $_size;

		public function __construct(
			string $elemName,
			string $name,
			string $type,
			string $tmpName,
			int    $error,
			int    $size
		) {
			$this->_elemName = $elemName;
			$this->_name = $name;
			$this->_type = $type;
			$this->_tmpName = $tmpName;
			$this->_error = $error;
			$this->_size = $size;
		}

		public function getElemName(): string {
			return $this->_elemName;
		}

		public function getName(): string {
			return $this->_name;
		}

		public function getType(): string {
			return $this->_type;
		}

		public function getTmpName(): string {
			return $this->_tmpName;
		}

		public function getError(): int {
			return $this->_error;
		}

		public function getSize(): int {
			return $this->_size;
		}

		public function getExtension(): string {
			return strtolower(pathinfo($this->getName(), PATHINFO_EXTENSION));
		}

		public function setUploadPath(string $path): void {
			$this->uploadPath = Helper::RemoveMultipleSlashesInUrl($path);
		}

		public function getUploadPath(): string {
			return $this->uploadPath;
		}

		public function toArray(): array {
			return [
				'name' => $this->getName(),
				'type' => $this->getType(),
				'tmp_name' => $this->getTmpName(),
				'error' => $this->getError(),
				'size' => $this->getSize(),
			];
		}

		public function validateFile(array $allowedExtensions = []): void {
			$this->isFileUploaded();
			$this->isFileFormatAllowed($allowedExtensions);
			$this->handleUploadFileError();
		}

		public function IsImage(): bool {
			return Media::IsImage($this->getExtension());
		}

		protected function isFileUploaded(): void {
			if (!is_uploaded_file($this->getTmpName())) {
				throw new UploadException("An unknown error occured while uploading the file");
			}
		}

		protected function isFileFormatAllowed(array $allowedExtensions = []): void {
			if (empty($allowedExtensions)) {
				throw new UploadException("You should define at least one supported extension");
			}

			$fileName = $this->getName();
			$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

			if (!Helper::IsNullOrEmpty($fileName) && !in_array($fileExtension, $allowedExtensions)) {
				$allowed = implode(", ", $allowedExtensions);
				throw new UploadException("File extension is not allowed! Allowed extensions: $allowed");
			}
		}

		protected function handleUploadFileError(): void {
			if ($this->getError() === UPLOAD_ERR_OK) {
				return;
			}

			switch ($this->getError()) {
				case UPLOAD_ERR_INI_SIZE:
					throw new UploadException("The uploaded file exceeds the upload_max_filesize directive in php.ini");
				case UPLOAD_ERR_FORM_SIZE:
					throw new UploadException("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.");
				case UPLOAD_ERR_PARTIAL:
					throw new UploadException("The uploaded file was only partially uploaded");
				case UPLOAD_ERR_NO_FILE:
					throw new UploadException("No file was uploaded");
				case UPLOAD_ERR_NO_TMP_DIR:
					throw new UploadException("Missing a temporary folder");
				case UPLOAD_ERR_CANT_WRITE:
					throw new UploadException("Failed to write file to disk");
				case UPLOAD_ERR_EXTENSION:
					throw new UploadException("A PHP extension stopped the file upload");
				default:
					throw new UploadException("Unknown upload error");
			}
		}
	}
