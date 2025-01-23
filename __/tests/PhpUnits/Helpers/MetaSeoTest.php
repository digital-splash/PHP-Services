<?php
	namespace DigitalSplash\Tests\Helpers;

	use DigitalSplash\Exceptions\InvalidArgumentException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Helpers\MetaSeo;
	use DigitalSplash\Helpers\Script;
	use DigitalSplash\Helpers\Style;
	use PHPUnit\Framework\TestCase;

	class MetaSeoTest extends TestCase {
		public function setUp(): void {
			MetaSeo::ClearMetaArray();
			MetaSeo::ClearPreHearArray();
			MetaSeo::ClearPostHearArray();

			parent::setUp();
		}

		public function testAddToMetaArraySuccess() {
			$this->assertEmpty(MetaSeo::GetMetaArray());

			MetaSeo::AddToMetaArray("test", []);
			$this->assertCount(1, MetaSeo::GetMetaArray());
		}

		public function testRemoveFromMetaArraySuccess() {
			$this->assertEmpty(MetaSeo::GetMetaArray());

			MetaSeo::AddToMetaArray("test", []);
			$this->assertCount(1, MetaSeo::GetMetaArray());

			MetaSeo::RemoveFromMetaArray("test");
			$this->assertEmpty(MetaSeo::GetMetaArray());
		}

		public function testInvalidArgumentFail() {
			MetaSeo::AddToMetaArray("test", []);

			$this->expectException(InvalidArgumentException::class);
			$this->expectExceptionMessage("Invalid argument \"type\" having the value \"\". Allowed value(s): \"meta, comment\"");
			MetaSeo::RenderFull();
		}

		public function testRenderFullSuccess() {
			MetaSeo::SetClientName("John Doe");
			MetaSeo::SetPreTitle("Software Engineer");
			MetaSeo::SetPostTitle("Home Page");
			MetaSeo::SetTitle("John Doe");
			MetaSeo::SetAuthor("John Doe");
			MetaSeo::SetKeywords([
				"John",
				"Doe",
				"Software Engineer",
				"PHP",
			]);
			MetaSeo::SetDescription("John Doe is a Software Engineer");
			MetaSeo::SetPhoto("https://john-doe.com/assets/img/logo-big.png");
			MetaSeo::SetUrl("https://john-doe.com");
			MetaSeo::SetRobots(true);
			MetaSeo::SetGoolgeSiteVerification("");
			MetaSeo::SetCopyright("2022. John Doe");
			MetaSeo::SetFacebookAppId("123456789");
			MetaSeo::SetFacebookAdmins("");
			MetaSeo::SetTwitterCard("testtt"); //Should default to: summary_large_image
			MetaSeo::SetFavicon("https://john-doe.com/assets/img/favicon.png");

			MetaSeo::AddToMetaArray("test", [
				"type" => "meta",
				"name" => "test",
				"content" => "This is a test text",
			]);

			MetaSeo::AddToPreHeadArray("pre_1", "<!-- Here Goes Pre Head Scripts 01 -->");
			MetaSeo::AddToPreHeadArray("pre_2", "<!-- Here Goes Pre Head Scripts 02 -->");

			MetaSeo::AddToPostHeadArray("post_1", "<!-- Here Goes Post Head Scripts 01 -->");
			MetaSeo::AddToPostHeadArray("post_2", "<!-- Here Goes Post Head Scripts 02 -->");

			Style::AddFile("file_1.css", "file_1");
			Style::AddFile("file_2.css", "file_2");
			Style::AddStyle("<link rel=\"stylesheet\" href=\"style_1.css\">", "style_1");
			Style::AddStyle("<link rel=\"stylesheet\" href=\"style_2.css\">", "style_2");

			Script::AddFile("file_1.js", "file_1");
			Script::AddFile("file_2.js", "file_2");
			Script::AddScript("<script src=\"script_1.js\"></script>", "script_1");
			Script::AddScript("<script src=\"script_2.js\"></script>", "script_2");

			$expected = Helper::GetContentFromFile(__DIR__ . "/../../_CommonFiles/MetaSeo/header.html");
			$expected = str_replace("\r\n", "\n", $expected);
			$actual = MetaSeo::RenderFull();

			$this->assertStringStartsWith($actual, $expected);
		}
	}

?>
