<?php
	namespace DigitalSplash\Media\Models;

	class Files {

		/**
		 * @var File[] $_files
		 */
		private array $_files;
		private array $_phpfiles;

		public function __construct(
			array $phpFiles
		) {
			$this->_files = [];
			$this->_phpfiles = $phpFiles;
		}

		/**
		 * @return File[]
		 */
		public function getFiles(): array {
			return $this->_files;
		}

		/**
		 * Convert the $_FILES array to an Array of Arrays that holds all the data needed to perform the upload later.
		 * This function is always called before we start the upload
		 */
		public function buildFiles(): void {
			$this->_files = [];

			foreach ($this->_phpfiles as $elemName => $elemPhpFiles) {
				$this->_files = array_merge(
					$this->_files,
					self::buildFilesForElement($elemName, $elemPhpFiles)
				);
			}
		}

		private function buildFilesForElement(string $elemName, array $elemFiles): array {
			$files = [];

			[
				'name' => $name,
				'type' => $type,
				'tmp_name' => $tmp_name,
				'error' => $error,
				'size' => $size
			] = $elemFiles;

			if (is_string($name)) {
				$files[] = new File("[{$elemName}]", $name, $type, $tmp_name, $error, $size);
			} else {
				foreach ($name as $i => $v) {
					$_elemFiles = [
						'name' => $name[$i],
						'type' => $type[$i],
						'tmp_name' => $tmp_name[$i],
						'error' => $error[$i],
						'size' => $size[$i]
					];
					$files = array_merge(
						$files,
						$this->buildFilesForElement("{$elemName}][{$i}", $_elemFiles)
					);
				}
			}

			return $files;
		}

	}
