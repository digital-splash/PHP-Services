<?php
	namespace DigitalSplash\Media\Models;

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

	}
