<?php
	namespace DigitalSplash\Tests\Helpers;


	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Helpers\Script;

	class ScriptTest extends TestCase {

		public function setUp(): void {
			Script::ClearFiles();
			Script::ClearScripts();

			parent::setUp();
		}

		public function testAddFileSuccess() {
			$this->assertEmpty(Script::GetFiles());

			Script::AddFile("");
			$this->assertCount(1, Script::GetFiles());
		}

		public function testRemoveFileSuccess() {
			$this->assertEmpty(Script::GetFiles());

			Script::AddFile("");
			$this->assertCount(1, Script::GetFiles());

			Script::RemoveFile(0);
			$this->assertEmpty(Script::GetFiles());
		}

		public function testAddScriptSuccess() {
			$this->assertEmpty(Script::GetScripts());

			Script::AddScript("");
			$this->assertCount(1, Script::GetScripts());
		}

		public function testRemoveScriptSuccess() {
			$this->assertEmpty(Script::GetScripts());

			Script::AddScript("", "test");
			$this->assertCount(1, Script::GetScripts());

			Script::RemoveScript("test");
			$this->assertEmpty(Script::GetScripts());
		}

		public function testGetFilesIncludesSuccess() {
			Script::AddFile("file_1.js", "file_1");
			Script::AddFile("file_2.js", "file_2");
			Script::AddFile("file_3.js", "file_3");

			Script::AddScript("<script src=\"script_1.js\"></script>", "script_1");
			Script::AddScript("<script src=\"script_2.js\"></script>", "script_2");
			Script::AddScript("<script src=\"script_3.js\"></script>", "script_3");

			$expected = Helper::GetContentFromFile(__DIR__ . "/../../_CommonFiles/Script/scripts.html");
			$actual = Script::GetFilesIncludes() . "\n";

			$this->assertEquals($expected, $actual);
		}

	}

?>
