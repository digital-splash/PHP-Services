<?php
	namespace DigitalSplash\Tests\Media\Models;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Media\Models\File;

	class FileTest extends TestCase {
		public function testGetters(): void {
			$elemName = "testElemName";
			$name = "testName";
			$type = "testType";
			$tmpName = "testTmpName";
			$error = 0;
			$size = "testSize";

			$file = new File($elemName, $name, $type, $tmpName, $error, $size);

			$this->assertEquals($elemName, $file->getElemName());
			$this->assertEquals($name, $file->getName());
			$this->assertEquals($type, $file->getType());
			$this->assertEquals($tmpName, $file->getTmpName());
			$this->assertEquals($error, $file->getError());
			$this->assertEquals($size, $file->getSize());
		}

	}