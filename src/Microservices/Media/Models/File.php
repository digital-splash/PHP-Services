<?php
	namespace DigitalSplash\Media\Models;

	use DigitalSplash\Exceptions\UploadException;

	class File {
		private string $_elemName;
		private string $_name;
		private string $_type;
		private string $_tmpName;
		private int $_error;
		private string $_size;

		public function __construct(
			string $elemName,
			string $name,
			string $type,
			string $tmpName,
			int $error,
			string $size
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

		public function getSize(): string {
			return $this->_size;
		}

		public function createFile(): array {
			$file = [
				"name"		=> $this->getName(),
				"type"		=> $this->getType(),
				"tmp_name"	=> $this->getTmpName(),
				"error"		=> $this->getError(),
				"size"		=> $this->getSize()
			];

			return $file;
		}

		public function isFileUploaded(): bool {
			return is_uploaded_file($this->getTmpName());
		}

		public function isFileFormatAllowed(array $allowedExtensions): bool {
			$fileName = $this->getName();
			$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

			if($fileName != "" && !in_array($fileExtension, $allowedExtensions)) {
				$allowed = implode(", ", $allowedExtensions);
				throw new UploadException("File extension is not allowed! Allowed extensions: $allowed");
			}

			return true;
		}

		public function handleUploadFileError(): void {
			//how do you prefer putting the cases as number or its corresponding variable as UPLOAD_ERR_INI_SIZE
			switch ($this->getError()) {
				case 1:
					throw new UploadException("The uploaded file exceeds the upload_max_filesize directive in php.ini");
				case 2:
					$maxSize	= $_POST["MAX_FILE_SIZE"];
					$maxSizeKb	= round($maxSize / 1024);
					throw new UploadException("The uploaded file is larger than the maximum allowed of $maxSizeKb Kb.");
				case 3:
					throw new UploadException("The uploaded file was only partially uploaded");
				case 4:
					throw new UploadException("No file was uploaded");
				case 6:
					throw new UploadException("Missing a temporary folder");
				case 7:
					throw new UploadException("Failed to write file to disk");
				case 8:
					throw new UploadException("A PHP extension stopped the file upload");
				default:
					throw new UploadException("Unknown upload error");
			}
		}

		public function validateFile(array $allowedExtensions): void {
			$this->isFileUploaded();
			$this->isFileFormatAllowed($allowedExtensions);
		}

	}
