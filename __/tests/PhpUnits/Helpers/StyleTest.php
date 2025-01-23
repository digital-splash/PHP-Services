<?php
	namespace DigitalSplash\Tests\Helpers;

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Helpers\Style;
	use PHPUnit\Framework\TestCase;

	class StyleTest extends TestCase {
		public function setUp(): void {
			Style::ClearFiles();
			Style::ClearStyles();

			parent::setUp();
		}

		public function testAddFileSuccess() {
			$this->assertEmpty(Style::GetFiles());

			Style::AddFile("");
			$this->assertCount(1, Style::GetFiles());
		}

		public function testRemoveFileSuccess() {
			$this->assertEmpty(Style::GetFiles());

			Style::AddFile("");
			$this->assertCount(1, Style::GetFiles());

			Style::RemoveFile(0);
			$this->assertEmpty(Style::GetFiles());
		}

		public function testAddStyleSuccess() {
			$this->assertEmpty(Style::GetStyles());

			Style::AddStyle("");
			$this->assertCount(1, Style::GetStyles());
		}

		public function testRemoveStyleSuccess() {
			$this->assertEmpty(Style::GetStyles());

			Style::AddStyle("", "test");
			$this->assertCount(1, Style::GetStyles());

			Style::RemoveStyle("test");
			$this->assertEmpty(Style::GetStyles());
		}

		public function testGetFilesIncludesSuccess() {
			Style::AddFile("file_1.css", "file_1");
			Style::AddFile("file_2.css", "file_2");
			Style::AddFile("file_3.css", "file_3");

			Style::AddStyle("<link rel=\"stylesheet\" href=\"style_1.css\">", "style_1");
			Style::AddStyle("<link rel=\"stylesheet\" href=\"style_2.css\">", "style_2");
			Style::AddStyle("<link rel=\"stylesheet\" href=\"style_3.css\">", "style_3");

			$expected = Helper::GetContentFromFile(__DIR__ . "/../../_CommonFiles/Style/styles.html");
			$expected = str_replace("\r\n", "\n", $expected);
			$actual = Style::GetFilesIncludes() . "\n";

			$this->assertEquals($expected, $actual);
		}
	}

?>
