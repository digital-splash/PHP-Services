<?php
	namespace DigitalSplash\Tests\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Exceptions\FileNotFoundException;
	use DigitalSplash\Exceptions\InvalidParamException;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Models\Code;
	use DigitalSplash\Models\HttpCode;
	use DigitalSplash\Language\Models\Lang;
	use DigitalSplash\Models\Status;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Language\Helpers\Translate;

	final class HelperTest extends TestCase {
		private const UPLOAD_DIR = __DIR__ . "/../../_CommonFiles/Upload/";

		public function testCleanStringSuccess(): void {
			$this->assertEquals(
				"John",
				Helper::CleanString(" John ")
			);

			$this->assertEquals(
				"John",
				Helper::CleanString(" John\\ ")
			);

			$this->assertEquals(
				"John\\",
				Helper::CleanString(" John\\\\ ")
			);
		}

		public function testCleanHtmlTextSuccess() {
			$this->assertEquals(
				"John",
				Helper::CleanHtmlText(" John ")
			);

			$this->assertEquals(
				"John&lt;br /&gt;",
				Helper::CleanHtmlText(" John<br /> ")
			);
		}

		public function testConvertToBoolSuccess() {
			//Type: String
			$this->assertTrue(
				Helper::ConvertToBool("John")
			);

			$this->assertFalse(
				Helper::ConvertToBool("false")
			);

			$this->assertFalse(
				Helper::ConvertToBool("")
			);

			//Type: Integer || Double
			$this->assertTrue(
				Helper::ConvertToBool(100)
			);

			$this->assertTrue(
				Helper::ConvertToBool(0.1)
			);

			$this->assertFalse(
				Helper::ConvertToBool(0)
			);

			//Type: Boolean
			$this->assertTrue(
				Helper::ConvertToBool(true)
			);

			$this->assertFalse(
				Helper::ConvertToBool(false)
			);

			//Type: Others
			$this->assertFalse(
				Helper::ConvertToBool(null)
			);
		}

		public function testConvertToIntSuccess() {
			$this->assertEquals(
				0,
				Helper::ConvertToInt("John")
			);

			$this->assertEquals(
				10,
				Helper::ConvertToInt("10")
			);

			$this->assertEquals(
				10,
				Helper::ConvertToInt(10)
			);

			$this->assertEquals(
				10,
				Helper::ConvertToInt(10.3)
			);

			$this->assertEquals(
				11,
				Helper::ConvertToInt(10.7)
			);

			$this->assertEquals(
				-10,
				Helper::ConvertToInt(-10.7)
			);
		}

		public function testConvertToDecSuccess() {
			$this->assertEquals(
				0,
				Helper::ConvertToDec("John")
			);

			$this->assertEquals(
				10,
				Helper::ConvertToDec("10")
			);

			$this->assertEquals(
				10.02,
				Helper::ConvertToDec("10.02")
			);

			$this->assertEquals(
				10.00,
				Helper::ConvertToDec("10.002")
			);

			$this->assertEquals(
				10.002,
				Helper::ConvertToDec("10.002", 3)
			);

			$this->assertEquals(
				-10.00,
				Helper::ConvertToDec("-10.002")
			);
		}

		public function testConvertToDecAsStringSuccess() {
			$this->assertEquals(
				"0",
				Helper::ConvertToDecAsString("John")
			);

			$this->assertEquals(
				"0.0",
				Helper::ConvertToDecAsString("John", 1)
			);

			$this->assertEquals(
				"10",
				Helper::ConvertToDecAsString("10")
			);

			$this->assertEquals(
				"10.00",
				Helper::ConvertToDecAsString("10", 2)
			);

			$this->assertEquals(
				"10.02",
				Helper::ConvertToDecAsString("10.02", 2)
			);

			$this->assertEquals(
				"10.00",
				Helper::ConvertToDecAsString("10.002", 2)
			);

			$this->assertEquals(
				"10.002",
				Helper::ConvertToDecAsString("10.002", 3)
			);
		}

		public function testIsNullOrEmptySuccess() {
			$this->assertTrue(
				Helper::IsNullOrEmpty(null)
			);

			$this->assertTrue(
				Helper::IsNullOrEmpty(0)
			);

			$this->assertTrue(
				Helper::IsNullOrEmpty('')
			);

			$this->assertTrue(
				Helper::IsNullOrEmpty('0')
			);

			$this->assertTrue(
				Helper::IsNullOrEmpty([])
			);

			$this->assertTrue(
				Helper::IsNullOrEmpty(json_decode(""))
			);

			$this->assertFalse(
				Helper::IsNullOrEmpty(1)
			);

			$this->assertFalse(
				Helper::IsNullOrEmpty("John")
			);

			$this->assertFalse(
				Helper::IsNullOrEmpty([
					"John"
				])
			);

			$this->assertFalse(
				Helper::IsNullOrEmpty(json_decode(json_encode([
					"John"
				])))
			);

			$this->assertFalse(
				Helper::IsNullOrEmpty(json_decode("{}"))
			);
		}

		public function testEncryptStringSuccess() {
			$this->assertEquals(
				"41b6d0cd5ddab15074f88bf1c356e89c3f330771b1c7a0b034bcdaafee74eb2ca2eca502f8c0b04fe5fd2f1ec5ae0197e0c6088f1cef6c07378b7f78bb64d9e4",
				Helper::EncryptString("John")
			);
		}

		public function testGenerateRandomKeySuccess() {
			$key1En = Helper::GenerateRandomKey(8, false, false, false, Lang::EN);
			$key1Ar = Helper::GenerateRandomKey(8, false, false, false, Lang::AR);

			$key2En = Helper::GenerateRandomKey(10, true, false, false, Lang::EN);
			$key2Ar = Helper::GenerateRandomKey(10, true, false, false, Lang::AR);

			$key3En = Helper::GenerateRandomKey(12, true, true, false, Lang::EN);
			$key3Ar = Helper::GenerateRandomKey(12, true, true, false, Lang::AR);

			$key4En = Helper::GenerateRandomKey(16, true, true, true, Lang::EN);
			$key4Ar = Helper::GenerateRandomKey(16, true, true, true, Lang::AR);

			$this->assertEquals(0, strlen($key1En));
			$this->assertEquals(0, strlen($key1Ar));

			$this->assertEquals(10, strlen($key2En));
			$this->assertEquals(10, strlen($key2Ar));

			$this->assertEquals(12, strlen($key3En));
			$this->assertEquals(12, strlen($key3Ar));

			$this->assertEquals(16, strlen($key4En));
			$this->assertEquals(16, strlen($key4Ar));

			//TODO: Should add assertion for validating possible strings
		}

		public function testRemoveSlashesSuccess() {
			$this->assertEquals(
				"John",
				Helper::RemoveSlashes("\\John\\\\")
			);

			$this->assertEquals(
				"John",
				Helper::RemoveSlashes("\John\\")
			);
		}

		public function testRemoveSpacesSuccess() {
			$this->assertEquals(
				"JohnDoe",
				Helper::RemoveSpaces("  J o h n  D o e ")
			);
		}

		public function testTruncateStrSuccess() {
			$this->assertEquals(
				"John Doe",
				Helper::TruncateStr("John Doe", 20)
			);

			$this->assertEquals(
				"John...",
				Helper::TruncateStr("John Doe", 4)
			);
		}

		public function testStringBeginsWithSuccess() {
			$this->assertTrue(
				Helper::StringBeginsWith("John Doe", "John")
			);

			$this->assertFalse(
				Helper::StringBeginsWith("John Doe", "Doe")
			);

			$this->assertTrue(
				Helper::StringBeginsWith("John Doe", ["John"])
			);

			$this->assertTrue(
				Helper::StringBeginsWith("John Doe", ["John", "Doe"])
			);

			$this->assertTrue(
				Helper::StringBeginsWith("John Doe", ["Joohn", "John"])
			);

			$this->assertFalse(
				Helper::StringBeginsWith("John Doe", ["Joohn", "Doe"])
			);

			$this->assertFalse(
				Helper::StringBeginsWith("John Doe", ["Joohn"])
			);
		}

		public function testStringEndsWithSuccess() {
			$this->assertTrue(
				Helper::StringEndsWith("John Doe", "Doe")
			);

			$this->assertFalse(
				Helper::StringEndsWith("John Doe", "John")
			);

			$this->assertTrue(
				Helper::StringEndsWith("John Doe", ["Doe"])
			);

			$this->assertTrue(
				Helper::StringEndsWith("John Doe", ["John", "Doe"])
			);

			$this->assertTrue(
				Helper::StringEndsWith("John Doe", ["Dooe", "Doe"])
			);

			$this->assertFalse(
				Helper::StringEndsWith("John Doe", ["John", "Dooe"])
			);

			$this->assertFalse(
				Helper::StringEndsWith("John Doe", ["Dooe"])
			);
		}

		public function testStringHasCharThrowError_01(): void {
			$this->expectException(InvalidParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.InvalidParam", null, [
				"::params::" => "search"
			]));
			Helper::StringHasChar("John Doe", 1);
		}

		public function testStringHasCharStringSuccess() {
			$this->assertTrue(
				Helper::StringHasChar("John Doe", "John")
			);

			$this->assertTrue(
				Helper::StringHasChar("John Doe", "Doe")
			);

			$this->assertFalse(
				Helper::StringHasChar("John Doe", "Doeee")
			);
		}

		public function testStringHasCharArraySuccess() {
			$this->assertTrue(
				Helper::StringHasChar("John Doe", ["John"])
			);

			$this->assertTrue(
				Helper::StringHasChar("John Doe", ["Joh", "Doe"])
			);

			$this->assertFalse(
				Helper::StringHasChar("John Doe", ["Johh", " Joh", "Doe "])
			);
		}

		public function testIsInStringSuccess() {
			$this->assertTrue(
				Helper::IsInString("John", "John Doe")
			);

			$this->assertTrue(
				Helper::IsInString("Doe", "John Doe")
			);

			$this->assertFalse(
				Helper::IsInString("Johnn", "John Doe")
			);
		}

		public function testStripHtmlSuccess() {
			$this->assertEquals(
				"John",
				Helper::StripHtml("<h1>John</h1><br />")
			);

			$this->assertEquals(
				"<h1>John</h1>",
				Helper::StripHtml("<h1>John</h1><br />", "<h1>")
			);

			$this->assertEquals(
				"<h1>John</h1><br />",
				Helper::StripHtml("<h1>John</h1><br />", ["<h1>", "<br>"])
			);
		}

		public function testTextReplaceSuccess() {
			$this->assertEquals(
				"John Doe",
				Helper::TextReplace("::FirstName:: ::LastName::", [
					"::FirstName::" => "John",
					"::LastName::" => "Doe",
				])
			);
		}

		public function testSplitCamelcaseStringSuccess() {
			$this->assertEquals(
				"John Matt Doe",
				Helper::SplitCamelcaseString("JohnMattDoe")
			);
		}

		public function testGetStringSafeSuccess() {
			$this->assertEquals(
				"",
				Helper::GetStringSafe(null)
			);

			$this->assertEquals(
				"",
				Helper::GetStringSafe("")
			);

			$this->assertEquals(
				"1",
				Helper::GetStringSafe(1)
			);

			$this->assertEquals(
				"John",
				Helper::GetStringSafe("John")
			);
		}

		public function testGenerateClassNameFromStringSuccess() {
			$this->assertEquals(
				"JohnMattDoe",
				Helper::GenerateClassNameFromString("john-matt-doe")
			);

			$this->assertEquals(
				"JohnMattDoe",
				Helper::GenerateClassNameFromString("John Matt Doe")
			);
		}

		public function testSafeNameSuccess() {
			$this->assertEquals(
				"johnmattdoe",
				Helper::SafeName("JohnMattDoe")
			);

			$this->assertEquals(
				"john-matt-doe",
				Helper::SafeName("John Matt Doe")
			);

			$this->assertEquals(
				"john-matt-doe",
				Helper::SafeName("John---Matt---Doe")
			);

			$this->assertEquals(
				"john-matt-doe",
				Helper::SafeName("John@Matt!$%Doe)({}")
			);

			$this->assertEquals(
				"جون-مات-دوه",
				Helper::SafeName("جون مات دوه!@#$%^&*()")
			);
		}

		public function testHasArabicCharSuccess() {
			$this->assertFalse(
				Helper::HasArabicChar("John Matt Doe")
			);

			$this->assertFalse(
				Helper::HasArabicChar("!@#$%^&*()")
			);

			$this->assertFalse(
				Helper::HasArabicChar("1234567890")
			);

			$this->assertTrue(
				Helper::HasArabicChar("جون مات دوه")
			);

			$this->assertTrue(
				Helper::HasArabicChar("John Matt Doe جون مات دوه")
			);
		}

		public function testExplodeStrToArrSuccess() {
			$this->assertEquals(
				[],
				Helper::ExplodeStrToArr(null)
			);

			$this->assertEquals(
				[],
				Helper::ExplodeStrToArr("")
			);

			$this->assertEquals(
				[
					"John Matt Doe",
				],
				Helper::ExplodeStrToArr("John Matt Doe")
			);

			$this->assertEquals(
				[
					"Jo",
					"hn",
					" M",
					"at",
					"t ",
					"Do",
					"e"
				],
				Helper::ExplodeStrToArr("", "John Matt Doe", 2)
			);

			$this->assertEquals(
				[
					"John",
					"Matt",
					"Doe",
				],
				Helper::ExplodeStrToArr("", "John Matt Doe")
			);

			$this->assertEquals(
				[
					"J",
					"hn Matt D",
					"e"
				],
				Helper::ExplodeStrToArr("o", "John Matt Doe")
			);
		}

		public function testImplodeArrToStrSuccess() {
			$this->assertEquals(
				"",
				Helper::ImplodeArrToStr(null)
			);

			$this->assertEquals(
				"",
				Helper::ImplodeArrToStr([])
			);

			$this->assertEquals(
				"John Matt Doe",
				Helper::ImplodeArrToStr([
					"John",
					"Matt",
					"Doe"
				])
			);

			$this->assertEquals(
				"John Matt Doe",
				Helper::ImplodeArrToStr([
					"John",
					"",
					"Matt",
					"",
					"Doe"
				])
			);
		}

		public function testGetValueFromArrByKeySuccess() {
			$this->assertEquals(
				"",
				Helper::GetValueFromArrByKey(null)
			);

			$this->assertEquals(
				"",
				Helper::GetValueFromArrByKey([])
			);

			$this->assertEquals(
				"",
				Helper::GetValueFromArrByKey([
					"key1" => "Key 1",
				], "key2")
			);

			$this->assertEquals(
				"Key 2",
				Helper::GetValueFromArrByKey([
					"key1" => "Key 1",
					"key2" => "Key 2",
				], "key2")
			);
		}

		public function testUnsetArrayEmptyValuesSuccess() {
			$this->assertEquals(
				[],
				Helper::UnsetArrayEmptyValues(null)
			);

			$this->assertEquals(
				[],
				Helper::UnsetArrayEmptyValues([])
			);

			$this->assertEquals(
				[
					"John",
					"Matt",
					"Doe"
				],
				Helper::UnsetArrayEmptyValues([
					"John",
					"Matt",
					"Doe"
				])
			);

			$this->assertEquals(
				[
					"John",
					"Matt",
					"Doe"
				],
				Helper::UnsetArrayEmptyValues([
					"John",
					"",
					"Matt",
					null,
					"Doe"
				])
			);
		}

		public function testGererateKeyValueStringFromArraySuccess() {
			$this->assertEquals(
				"",
				Helper::GererateKeyValueStringFromArray(null)
			);

			$this->assertEquals(
				"",
				Helper::GererateKeyValueStringFromArray([])
			);

			$this->assertEquals(
				'type="text" name="test-input" id="test-input" placeholder="Test Input"',
				Helper::GererateKeyValueStringFromArray([
					"type" => "text",
					"name" => "test-input",
					"id" => "test-input",
					"placeholder" => "Test Input",
				])
			);

			$this->assertEquals(
				'pre_type="text" pre_name="test-input" pre_id="test-input" pre_placeholder="Test Input"',
				Helper::GererateKeyValueStringFromArray([
					"type" => "text",
					"name" => "test-input",
					"id" => "test-input",
					"placeholder" => "Test Input",
				], "pre_")
			);

			$this->assertEquals(
				'type:"text" name:"test-input" id:"test-input" placeholder:"Test Input"',
				Helper::GererateKeyValueStringFromArray([
					"type" => "text",
					"name" => "test-input",
					"id" => "test-input",
					"placeholder" => "Test Input",
				], "", ":")
			);

			$this->assertEquals(
				'type=-text- name=-test-input- id=-test-input- placeholder=-Test Input-',
				Helper::GererateKeyValueStringFromArray([
					"type" => "text",
					"name" => "test-input",
					"id" => "test-input",
					"placeholder" => "Test Input",
				], "", "=", "-")
			);

			$this->assertEquals(
				'type="text"_join_name="test-input"_join_id="test-input"_join_placeholder="Test Input"',
				Helper::GererateKeyValueStringFromArray([
					"type" => "text",
					"name" => "test-input",
					"id" => "test-input",
					"placeholder" => "Test Input",
				], "", "=", "\"", "_join_")
			);
		}

		public function testDirExistsSuccess() {
			$this->assertFalse(
				Helper::DirExists(null)
			);

			$this->assertFalse(
				Helper::DirExists("")
			);

			$this->assertFalse(
				Helper::DirExists("_CommonFiles", __DIR__ . "/")
			);

			$this->assertTrue(
				Helper::DirExists("PhpUnits", __DIR__ . "/../../")
			);

			$this->assertFalse(
				Helper::DirExists("_CommonFiles", __DIR__ . "/../../../")
			);

			$this->assertTrue(
				Helper::DirExists("_CommonFiles", __DIR__ . "/../../../", true)
			);
		}

		public function testCreateFolderSuccess(): void {
			$this->assertFalse(
				Helper::DirExists("NewFolder", self::UPLOAD_DIR)
			);
			$this->assertTrue(
				Helper::CreateFolder(self::UPLOAD_DIR . "NewFolder")
			);
		}

		public function testCreateFolderFail(): void {
			$this->assertTrue(
				Helper::DirExists("NewFolder", self::UPLOAD_DIR)
			);
			$this->assertFalse(
				Helper::CreateFolder(self::UPLOAD_DIR . "NewFolder")
			);
		}

		public function testDeleteFolderSuccess(): void {
			$this->assertTrue(
				Helper::DirExists("NewFolder", self::UPLOAD_DIR)
			);
			$this->assertTrue(
				Helper::DeleteFileOrFolder(self::UPLOAD_DIR . "NewFolder")
			);
		}

		public function testDeleteFileSuccess(): void {
			$newFile = self::UPLOAD_DIR . "unit-test.txt";

			$this->assertFalse(
				file_exists($newFile)
			);
			copy(self::UPLOAD_DIR . "test.txt", $newFile);
			$this->assertTrue(
				file_exists($newFile)
			);

			$this->assertTrue(
				Helper::DeleteFileOrFolder($newFile)
			);
		}

		public function testGetYoutubeIdSuccess() {
			$this->assertEquals(
				"",
				Helper::GetYoutubeId(null)
			);

			$this->assertEquals(
				"",
				Helper::GetYoutubeId("")
			);

			$this->assertEquals(
				"IZbN_nmxAGk",
				Helper::GetYoutubeId("https://www.youtube.com/embed/IZbN_nmxAGk")
			);
		}

		public function testEncryptLinkSuccess() {
			$this->assertEquals(
				"",
				Helper::EncryptLink(null)
			);

			$this->assertEquals(
				"",
				Helper::EncryptLink("")
			);

			$this->assertEquals(
				str_replace("&", "[amp;]", base64_encode("https://john-doe.com/projects?page=2&category=2")),
				Helper::EncryptLink("https://john-doe.com/projects?page=2&category=2")
			);
		}

		public function testDecryptLinkSuccess() {
			$this->assertEquals(
				"",
				Helper::DecryptLink(null)
			);

			$this->assertEquals(
				"",
				Helper::DecryptLink("")
			);

			$this->assertEquals(
				"https://john-doe.com/projects?page=2&category=2",
				Helper::DecryptLink(str_replace("&", "[amp;]", base64_encode("https://john-doe.com/projects?page=2&category=2")))
			);
		}

		public function GetStatusClassFromCodeProvider(): array {
			return [
				[Code::SUCCESS, Status::SUCCESS],
				[HttpCode::OK, Status::SUCCESS],
				[HttpCode::CREATED, Status::SUCCESS],
				[HttpCode::ACCEPTED, Status::SUCCESS],

				[Code::ERROR, Status::ERROR],
				[HttpCode::BADREQUEST, Status::ERROR],
				[HttpCode::UNAUTHORIZED, Status::ERROR],
				[HttpCode::FORBIDDEN, Status::ERROR],
				[HttpCode::NOTFOUND, Status::ERROR],
				[HttpCode::NOTALLOWED, Status::ERROR],
				[HttpCode::INTERNALERROR, Status::ERROR],
				[HttpCode::UNAVAILABLE, Status::ERROR],

				[Code::WARNING, Status::WARNING],

				[Code::INFO, Status::INFO],
				[Code::COMMON_INFO, Status::INFO],
				[HttpCode::CONTINUE, Status::INFO],
				[HttpCode::PROCESSING, Status::INFO],
			];
		}

		/**
		 * @dataProvider GetStatusClassFromCodeProvider
		 *
		 * @param $givenCode int
		 * @param $expectedStatus string
		 */
		public function testGetStatusClassFromCodeSuccess(int $givenCode, string $expectedStatus) {
			$this->assertEquals(
				$expectedStatus,
				Helper::GetStatusClassFromCode($givenCode)
			);
		}

		public function testGetHtmlContentFromFileThrowError_01(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "filePath"
			]));
			Helper::GetContentFromFile(null);
		}

		public function testGetHtmlContentFromFileThrowError_02(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "filePath"
			]));
			Helper::GetContentFromFile("");
		}

		public function testGetHtmlContentFromFileThrowError_03(): void {
			$this->expectException(FileNotFoundException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.FileNotFound", null, [
				"::params::" => "filePath"
			]));
			Helper::GetContentFromFile(__DIR__ . "/../../_CommonFiles/randomfile.html");
		}

		public function testGetHtmlContentFromFileWithoutReplaceSuccess() {
			$this->assertEquals(
				"<h1>testGetHtmlContentFromFileWithoutReplaceSuccess</h1>",
				Helper::GetContentFromFile(__DIR__ . "/../../_CommonFiles/testGetHtmlContentFromFileWithoutReplaceSuccess.html")
			);
		}

		public function testGetHtmlContentFromFileWithReplaceSuccess() {
			$this->assertEquals(
				"<h1>testGetHtmlContentFromFileWithReplaceSuccess</h1>\n<h2>Replaced Text 01</h2>\n<h3>Replaced Text 02</h3>",
				Helper::GetContentFromFile(__DIR__ . "/../../_CommonFiles/testGetHtmlContentFromFileWithReplaceSuccess.html", [
					"::replace_1::" => "Replaced Text 01",
					"::replace_2::" => "Replaced Text 02",
				])
			);
		}

		public function testGetJsonContentFromFileAsArrayThrowError_01(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "filePath"
			]));
			Helper::GetJsonContentFromFileAsArray(null);
		}

		public function testGetJsonContentFromFileAsArrayThrowError_02(): void {
			$this->expectException(NotEmptyParamException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.NotEmptyParam", null, [
				"::params::" => "filePath"
			]));
			Helper::GetJsonContentFromFileAsArray("");
		}

		public function testGetJsonContentFromFileAsArrayThrowError_03(): void {
			$this->expectException(FileNotFoundException::class);
			$this->expectExceptionMessage(Translate::TranslateString("exception.FileNotFound", null, [
				"::params::" => "filePath"
			]));
			Helper::GetJsonContentFromFileAsArray(__DIR__ . "/../../_CommonFiles/randomfile.json");
		}

		public function testGetJsonContentFromFileAsArraySuccess() {
			$this->assertEquals(
				[
					"fullName" => [
						"firstName" => "John",
						"middleName" => "Matt",
						"lastName" => "Doe",
					],
					"position" => "Senior Software Engineer",
					"languages" => [
						"Arabic",
						"English",
						"French",
						"Spanish",
					]
				],
				Helper::GetJsonContentFromFileAsArray(__DIR__ . "/../../_CommonFiles/testGetJsonContentFromFileAsArraySuccess.json")
			);
		}

		public function testGenerateFullUrlSuccess() {
			$this->assertEquals(
				"home",
				Helper::GenerateFullUrl("home")
			);

			$this->assertEquals(
				"home/en",
				Helper::GenerateFullUrl("home", Lang::EN)
			);

			$this->assertEquals(
				"home?lang=en",
				Helper::GenerateFullUrl("home", Lang::EN, [], [], "", false)
			);

			$this->assertEquals(
				"products/en/product-001",
				Helper::GenerateFullUrl("products", Lang::EN, [
					"key" => "product-001"
				])
			);

			$this->assertEquals(
				"products?lang=en&key=product-001",
				Helper::GenerateFullUrl("products", Lang::EN, [
					"key" => "product-001"
				], [], "", false)
			);

			$this->assertEquals(
				"products/en/product-001?filter=active",
				Helper::GenerateFullUrl("products", Lang::EN, [
					"key" => "product-001"
				], [
					"filter" => "active"
				])
			);

			$this->assertEquals(
				"products?lang=en&key=product-001&filter=active",
				Helper::GenerateFullUrl("products", Lang::EN, [
					"key" => "product-001"
				], [
					"filter" => "active"
				], "", false)
			);

			$this->assertEquals(
				"products/en/product-001?filter=active&categories%5B%5D=category-01&categories%5B%5D=category-02&categories%5B%5D=category-03",
				Helper::GenerateFullUrl("products", Lang::EN, [
					"key" => "product-001"
				], [
					"filter" => "active",
					"categories" => [
						"category-01",
						"category-02",
						"category-03",
					]
				])
			);

			$this->assertEquals(
				"https://john-doe.com/home/en",
				Helper::GenerateFullUrl("home", Lang::EN, [], [], "https://john-doe.com/")
			);

			$this->assertEquals(
				"https://john-doe.com/home/en",
				Helper::GenerateFullUrl("home", Lang::EN, [], [], "https://john-doe.com")
			);

			$this->assertEquals(
				"https://john-doe.com/home/en",
				Helper::GenerateFullUrl("home", Lang::EN, [], [], "https://john-doe.com////")
			);

			$this->assertEquals(
				"www.john-doe.com/home/en",
				Helper::GenerateFullUrl("home", Lang::EN, [], [], "www.john-doe.com////")
			);
		}

		public function testAddVersionParameterToPathSuccess() {
			$this->assertEquals(
				"assets/css/styles.css",
				Helper::AddVersionParameterToPath("assets/css/styles.css", "")
			);

			$this->assertEquals(
				"https://john-doe.com/assets/css/styles.css",
				Helper::AddVersionParameterToPath("assets/css/styles.css", "https://john-doe.com")
			);

			$this->assertEquals(
				"https://john-doe.com/assets/css/styles.css?v=1.0",
				Helper::AddVersionParameterToPath("assets/css/styles.css", "https://john-doe.com", "1.0")
			);
		}

		public function testGetAllFilesSuccess() {
			$dir = str_replace("\PhpUnits\Helpers", "\_CommonFiles\Recursive", __DIR__);

			$this->assertEqualsCanonicalizing(
				[
					$dir . "/file1.html",
					$dir . "/file2.html",
				],
				Helper::GetAllFiles($dir, false)
			);

			$this->assertEqualsCanonicalizing(
				[
					$dir . "/file1.html",
					$dir . "/file2.html",
					$dir . "/Folder1/file1.html",
					$dir . "/Folder1/file2.html",
					$dir . "/Folder2/file1.html",
					$dir . "/Folder2/file2.html",
				],
				Helper::GetAllFiles($dir, true)
			);
		}

		public function testConvertMultidimentionArrayToSingleDimentionSuccess() {
			$this->assertEquals([
				"name.first" => "John",
				"name.middle" => "Matt",
				"name.last" => "Doe",
				"address.building" => "Bldg",
				"address.street" => "Street",
				"address.region" => "Region",
				"address.country" => "Lebanon",
				"contact.info.mobile" => "+961111111",
				"contact.info.email" => "email@test.com",
			], Helper::ConvertMultidimentionArrayToSingleDimention([
				"name" => [
					"first" => "John",
					"middle" => "Matt",
					"last" => "Doe",
				],
				"address" => [
					"building" => "Bldg",
					"street" => "Street",
					"region" => "Region",
					"country" => "Lebanon"
				],
				"contact" => [
					"info" => [
						"mobile" => "+961111111",
						"email" => "email@test.com"
					]
				]
			]));
		}

		public function testAddSchemeIfMissingSuccess() {
			$this->assertEquals(
				"",
				Helper::AddSchemeIfMissing("", "")
			);

			$this->assertEquals(
				"john-doe.com",
				Helper::AddSchemeIfMissing("john-doe.com", "")
			);

			$this->assertEquals(
				"http://john-doe.com",
				Helper::AddSchemeIfMissing("http://john-doe.com", "https://")
			);

			$this->assertEquals(
				"https://john-doe.com",
				Helper::AddSchemeIfMissing("https://john-doe.com", "http://")
			);

			$this->assertEquals(
				"https://john-doe.com",
				Helper::AddSchemeIfMissing("john-doe.com", "https")
			);

			$this->assertEquals(
				"https://john-doe.com",
				Helper::AddSchemeIfMissing("john-doe.com", "https://")
			);
		}

		public function testReplaceSchemeSuccess() {
			$this->assertEquals(
				"",
				Helper::ReplaceScheme("", "")
			);

			$this->assertEquals(
				"john-doe.com",
				Helper::ReplaceScheme("john-doe.com", "")
			);

			$this->assertEquals(
				"https://john-doe.com",
				Helper::ReplaceScheme("http://john-doe.com", "https://")
			);

			$this->assertEquals(
				"http://john-doe.com",
				Helper::ReplaceScheme("https://john-doe.com", "http://")
			);

			$this->assertEquals(
				"https://john-doe.com",
				Helper::ReplaceScheme("john-doe.com", "https")
			);

			$this->assertEquals(
				"https://john-doe.com",
				Helper::ReplaceScheme("john-doe.com", "https://")
			);
		}

		public function testIsValidUrl() {
			$this->assertFalse(
				Helper::IsValidUrl("John Doe")
			);

			$this->assertFalse(
				Helper::IsValidUrl("John Doe: https://john-doe.com")
			);

			$this->assertFalse(
				Helper::IsValidUrl("http//john-doe.com")
			);

			$this->assertFalse(
				Helper::IsValidUrl("http:/john-doe.com")
			);

			$this->assertFalse(
				Helper::IsValidUrl("http:john-doe.com")
			);

			$this->assertFalse(
				Helper::IsValidUrl("https:/john-doe.com")
			);

			$this->assertTrue( //To be fixed!
				Helper::IsValidUrl("http://john-doe.com John Doe")
			);

			$this->assertTrue(
				Helper::IsValidUrl("http://john-doe.com")
			);

			$this->assertTrue(
				Helper::IsValidUrl("https://john-doe.com")
			);
		}

	}
