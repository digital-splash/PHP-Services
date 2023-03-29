<?php
	namespace DigitalSplash\Tests\Helpers;


	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Exceptions\InvalidArgumentException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Helpers\MetaSeo;
	use DigitalSplash\Helpers\Script;
	use DigitalSplash\Helpers\Style;

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
			MetaSeo::SetClientName("Mario Rawady");
			MetaSeo::SetPreTitle("Software Engineer");
			MetaSeo::SetPostTitle("Home Page");
			MetaSeo::SetTitle("Mario Rawady");
			MetaSeo::SetAuthor("Mario Rawady");
			MetaSeo::SetKeywords([
				"Mario",
				"Rawady",
				"Software Engineer",
				"PHP",
			]);
			MetaSeo::SetDescription("Mario Rawady is a Software Engineer");
			MetaSeo::SetPhoto("https://dg-splash.com/assets/img/logo-big.png");
			MetaSeo::SetUrl("https://dg-splash.com");
			MetaSeo::SetRobots(true);
			MetaSeo::SetGoolgeSiteVerification("");
			MetaSeo::SetCopyright("2022. Digital Splash");
			MetaSeo::SetFacebookAppId("123456789");
			MetaSeo::SetFacebookAdmins("");
			MetaSeo::SetTwitterCard("testtt"); //Should default to: summary_large_image
			MetaSeo::SetFavicon("https://dg-splash.com/assets/img/favicon.png");

			MetaSeo::AddToMetaArray("test", [
				"type" => "meta",
				"name" => "test",
				"content" => "This is a test text"
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

			$this->assertEquals($expected, $actual);
		}

	}

?>
