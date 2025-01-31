<?php

	namespace DigitalSplash\Tests\Helpers;

	use DigitalSplash\Exceptions\Media\FileNotFoundException;
	use DigitalSplash\Exceptions\Validation\NotEmptyParamException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Microservices\Language\Models\Language;
	use DigitalSplash\Models\Code;
	use DigitalSplash\Models\HttpCode;
	use DigitalSplash\Models\Status;
	use PHPUnit\Framework\TestCase;

	// Was about 1,450 lines
	final class HelperTest extends TestCase {
		public function testCleanString(): void {
			$this->assertEquals('John', Helper::cleanString(' John '));
			$this->assertEquals('John', Helper::cleanString(' John\\ '));
			$this->assertEquals('John\\', Helper::cleanString(' John\\\\ '));
		}

		public function testCleanHtmlText(): void {
			$this->assertEquals('John', Helper::cleanHtmlText(' John '));
			$this->assertEquals('John&lt;br /&gt;', Helper::cleanHtmlText(' John<br /> '));
			$this->assertEquals('John<br />', Helper::cleanHtmlText(' John<br /> ', false));
		}

		public function testGetStringSafe(): void {
			$this->assertEquals('', Helper::getStringSafe(null));
			$this->assertEquals('', Helper::getStringSafe(''));
			$this->assertEquals('1', Helper::getStringSafe(1));
			$this->assertEquals('John', Helper::getStringSafe('John'));
		}

		public function testIsNullOrEmpty(): void {
			$this->assertTrue(Helper::isNullOrEmpty(null));
			$this->assertTrue(Helper::isNullOrEmpty(0));
			$this->assertTrue(Helper::isNullOrEmpty(''));
			$this->assertTrue(Helper::isNullOrEmpty('0'));
			$this->assertTrue(Helper::isNullOrEmpty([]));
			$this->assertTrue(Helper::isNullOrEmpty(json_decode('')));
			$this->assertFalse(Helper::isNullOrEmpty(1));
			$this->assertFalse(Helper::isNullOrEmpty('John'));
			$this->assertFalse(Helper::isNullOrEmpty(['John']));
			$this->assertFalse(Helper::isNullOrEmpty(json_decode(json_encode(['John']))));
			$this->assertFalse(Helper::isNullOrEmpty(json_decode('{}')));
		}

		public function testConvertToInt(): void {
			$this->assertEquals(0, Helper::convertToInt(null));
			$this->assertEquals(0, Helper::convertToInt('John'));
			$this->assertEquals(10, Helper::convertToInt('10'));
			$this->assertEquals(10, Helper::convertToInt(10));
			$this->assertEquals(10, Helper::convertToInt(10.3));
			$this->assertEquals(11, Helper::convertToInt(10.7));
			$this->assertEquals(-10, Helper::convertToInt(-10.3));
			$this->assertEquals(-11, Helper::convertToInt(-10.7));
		}

		public function testConvertToDec(): void {
			$this->assertEquals(0, Helper::convertToDec(null));
			$this->assertEquals(0, Helper::convertToDec('John'));
			$this->assertEquals(10, Helper::convertToDec('10'));
			$this->assertEquals(10.02, Helper::convertToDec(10.02));
			$this->assertEquals(10, Helper::convertToDec(10.002));
			$this->assertEquals(10.002, Helper::convertToDec(10.002, 3));
			$this->assertEquals(10.002, Helper::convertToDec('10.002', 3));
			$this->assertEquals(-10, Helper::convertToDec('-10.002'));
		}

		public function testConvertToDecAsString(): void {
			$this->assertEquals('0.00', Helper::convertToDecAsString(null));
			$this->assertEquals('0.00', Helper::convertToDecAsString('John'));
			$this->assertEquals('0.0', Helper::convertToDecAsString('John', 1));
			$this->assertEquals('10.00', Helper::convertToDecAsString('10'));
			$this->assertEquals('10.02', Helper::convertToDecAsString(10.02));
			$this->assertEquals('10.00', Helper::convertToDecAsString(10.002));
			$this->assertEquals('10.002', Helper::convertToDecAsString(10.002, 3));
			$this->assertEquals('10.002', Helper::convertToDecAsString('10.002', 3));
			$this->assertEquals('-10.00', Helper::convertToDecAsString('-10.002'));
		}

		public function testConvertToBool(): void {
			//Type: Boolean
			$this->assertTrue(Helper::convertToBool(true));
			$this->assertFalse(Helper::convertToBool(false));

			//Type: String
			$this->assertTrue(Helper::convertToBool('John'));
			$this->assertFalse(Helper::convertToBool('false'));
			$this->assertFalse(Helper::convertToBool(''));

			//Type: Integer || Double
			$this->assertTrue(Helper::convertToBool(100));
			$this->assertTrue(Helper::convertToBool(0.1));
			$this->assertFalse(Helper::convertToBool(0));
			$this->assertFalse(Helper::convertToBool(0.00));

			//Type: Others
			$this->assertFalse(Helper::convertToBool(null));
			$this->assertFalse(Helper::convertToBool([]));
			$this->assertTrue(Helper::convertToBool(['John']));
		}

		public function testEncryptString(): void {
			$encryptedValue = '41b6d0cd5ddab15074f88bf1c356e89c3f330771b1c7a0b034bcdaafee74eb2ca2eca502f8c0b04fe5fd2f1ec5ae0197e0c6088f1cef6c07378b7f78bb64d9e4';

			$this->assertEquals($encryptedValue, Helper::encryptString('John'));
		}

		public function testIsEncrypted(): void {
			$plain = 'John';
			$encryptedValue = Helper::encryptString($plain);

			$this->assertFalse(Helper::isEncrypted($plain));
			$this->assertTrue(Helper::isEncrypted($encryptedValue));
		}

		public function testGenerateRandomString(): void {
			$key1En = Helper::generateRandomString(8, false, false, false, Language::EN);
			$key1Ar = Helper::generateRandomString(8, false, false, false, Language::AR);

			$key2En = Helper::generateRandomString(10, true, false, false, Language::EN);
			$key2Ar = Helper::generateRandomString(10, true, false, false, Language::AR);

			$key3En = Helper::generateRandomString(12, true, true, false, Language::EN);
			$key3Ar = Helper::generateRandomString(12, true, true, false, Language::AR);

			$key4En = Helper::generateRandomString(16, true, true, true, Language::EN);
			$key4Ar = Helper::generateRandomString(16, true, true, true, Language::AR);

			$this->assertEquals(0, strlen($key1En));
			$this->assertEquals(0, strlen($key1Ar));

			$this->assertEquals(10, strlen($key2En));
			$this->assertEquals(10, strlen($key2Ar));

			$this->assertEquals(12, strlen($key3En));
			$this->assertEquals(12, strlen($key3Ar));

			$this->assertEquals(16, strlen($key4En));
			$this->assertEquals(16, strlen($key4Ar));
		}

		public function testRemoveSlashes(): void {
			$this->assertEquals('John', Helper::removeSlashes('\\John\\\\'));
			$this->assertEquals('John', Helper::removeSlashes('\John\\'));
		}

		public function testRemoveSpaces(): void {
			$this->assertEquals('JohnDoe', Helper::removeSpaces('  J o h n  D o e '));
		}

		public function testTruncateStr(): void {
			$this->assertEquals('John Doe', Helper::truncateStr('John Doe', 20));
			$this->assertEquals('John...', Helper::truncateStr('John Doe', 4));
			$this->assertEquals('جون دوه', Helper::truncateStr('جون دوه', 20, '...', Language::AR));
			$this->assertEquals('جون...', Helper::truncateStr('جون دوه', 3, '...', Language::AR));
		}

		public function testStringBeginsWith(): void {
			$this->assertFalse(Helper::stringBeginsWith('John Doe', null));
			$this->assertFalse(Helper::stringBeginsWith('John Doe', ''));
			$this->assertFalse(Helper::stringBeginsWith('John Doe', 1));
			$this->assertTrue(Helper::stringBeginsWith('John Doe', 'John'));
			$this->assertFalse(Helper::stringBeginsWith('John Doe', 'Doe'));
			$this->assertTrue(Helper::stringBeginsWith('John Doe', ['John']));
			$this->assertTrue(Helper::stringBeginsWith('John Doe', ['John', 'Doe']));
			$this->assertTrue(Helper::stringBeginsWith('John Doe', ['Joohn', 'John']));
			$this->assertFalse(Helper::stringBeginsWith('John Doe', ['Joohn', 'Doe']));
			$this->assertFalse(Helper::stringBeginsWith('John Doe', ['Joohn']));
		}

		public function testStringEndsWith(): void {
			$this->assertFalse(Helper::stringEndsWith('John Doe', null));
			$this->assertFalse(Helper::stringEndsWith('John Doe', ''));
			$this->assertFalse(Helper::stringEndsWith('John Doe', 1));
			$this->assertTrue(Helper::stringEndsWith('John Doe', 'Doe'));
			$this->assertFalse(Helper::stringEndsWith('John Doe', 'John'));
			$this->assertTrue(Helper::stringEndsWith('John Doe', ['Doe']));
			$this->assertTrue(Helper::stringEndsWith('John Doe', ['John', 'Doe']));
			$this->assertTrue(Helper::stringEndsWith('John Doe', ['Dooe', 'Doe']));
			$this->assertFalse(Helper::stringEndsWith('John Doe', ['John', 'Dooe']));
			$this->assertFalse(Helper::stringEndsWith('John Doe', ['Dooe']));
		}

		public function testStringHasChar(): void {
			$this->assertFalse(Helper::stringHasChar('John Doe', null));
			$this->assertFalse(Helper::stringHasChar('John Doe', ''));
			$this->assertFalse(Helper::stringHasChar('John Doe', 1));
			$this->assertTrue(Helper::stringHasChar('John Doe', 'John'));
			$this->assertTrue(Helper::stringHasChar('John Doe', 'Doe'));
			$this->assertFalse(Helper::stringHasChar('John Doe', 'Doeee'));

			$this->assertTrue(Helper::stringHasChar('John Doe', ['John']));
			$this->assertTrue(Helper::stringHasChar('John Doe', ['Joh']));
			$this->assertTrue(Helper::stringHasChar('John Doe', ['Johh', 'Doe']));
			$this->assertFalse(Helper::stringHasChar('John Doe', ['Johh', ' Joh', 'Doe ']));
		}

		public function testStripHtml(): void {
			$this->assertEquals('John', Helper::stripHtml('<h1>John</h1><br />'));
			$this->assertEquals('<h1>John</h1>', Helper::stripHtml('<h1>John</h1><br />', '<h1>'));
			$this->assertEquals('<h1>John</h1><br />', Helper::stripHtml('<h1>John</h1><br />', ['<h1>', '<br>']));
		}

		public function testTextReplace(): void {
			$this->assertEquals(
				'John Doe',
				Helper::textReplace('::FirstName:: ::LastName::', [
					'::FirstName::' => 'John',
					'::LastName::' => 'Doe',
				])
			);
		}

		public function testSplitCamelcaseString(): void {
			$this->assertEquals('John Matt Doe', Helper::splitCamelcaseString('JohnMattDoe'));
		}

		public function testConvertStringToCamelcase(): void {
			$this->assertEquals('JohnMattDoe', Helper::convertStringToCamelcase('john matt Doe'));
			$this->assertEquals('JohnMattDoe', Helper::convertStringToCamelcase('JOHN MATT DOE'));
			$this->assertEquals('JohnMattDoe', Helper::convertStringToCamelcase('John Matt Doe'));
			$this->assertEquals('JohnMattdoe', Helper::convertStringToCamelcase('John MattDoe'));
			$this->assertEquals('JohnMattDoe', Helper::convertStringToCamelcase('john-matt_doe'));
		}

		public function testHasArabicChar(): void {
			$this->assertFalse(Helper::hasArabicChar('John Matt Doe'));
			$this->assertFalse(Helper::hasArabicChar('!@#$%^&*()'));
			$this->assertFalse(Helper::hasArabicChar('1234567890'));
			$this->assertTrue(Helper::hasArabicChar('٠١٢٣٤٥٦٧٨٩'));
			$this->assertTrue(Helper::hasArabicChar('جون مات دوه'));
			$this->assertTrue(Helper::hasArabicChar('John Matt Doe جون مات دوه'));
		}

		public function testSafeName(): void {
			$this->assertEquals('johnmattdoe', Helper::safeName('JohnMattDoe'));
			$this->assertEquals('john-matt-doe', Helper::safeName('John Matt Doe'));
			$this->assertEquals('john-matt-doe', Helper::safeName('John---Matt---Doe'));
			$this->assertEquals('john-matt-doe', Helper::safeName('John@Matt!$%Doe)({}'));
			$this->assertEquals('جون-مات-دوه', Helper::safeName('جون مات دوه!@#$%^&*()'));
		}

		public function testExplode(): void {
			$this->assertEquals(
				[],
				Helper::explode('', null)
			);

			$this->assertEquals(
				[],
				Helper::explode('', '')
			);

			$this->assertEquals(
				[
					'John Matt Doe',
				],
				Helper::explode('', 'John Matt Doe')
			);

			$this->assertEquals(
				[
					'Jo',
					'hn',
					' M',
					'at',
					't ',
					'Do',
					'e',
				],
				Helper::explode('', 'John Matt Doe', 2)
			);

			$this->assertEquals(
				[
					'John',
					'Matt',
					'Doe',
				],
				Helper::explode(' ', 'John Matt Doe')
			);

			$this->assertEquals(
				[
					'J',
					'hn Matt D',
					'e',
				],
				Helper::explode('o', 'John Matt Doe')
			);

			$this->assertEquals(
				[
					'Test1',
					'Test2',
				],
				Helper::explode('/', '/Test1/Test2/')
			);
		}

		public function testImplode(): void {
			$this->assertEquals(
				'',
				Helper::implode('', null)
			);

			$this->assertEquals(
				'',
				Helper::implode('', [])
			);

			$this->assertEquals(
				'John Matt Doe',
				Helper::implode(' ', [
					'John',
					'Matt',
					'Doe',
				])
			);

			$this->assertEquals(
				'John Matt Doe',
				Helper::implode(' ', [
					'John',
					'',
					'Matt',
					'',
					'Doe',
				])
			);
		}

		public function testUnsetArrayEmptyValues(): void {
			$this->assertEquals(
				[],
				Helper::unsetArrayEmptyValues(null)
			);

			$this->assertEquals(
				[],
				Helper::unsetArrayEmptyValues([])
			);

			$this->assertEquals(
				[
					'John',
					'Matt',
					'Doe',
				],
				Helper::unsetArrayEmptyValues([
					'John',
					'Matt',
					'Doe',
				])
			);

			$this->assertEquals(
				[
					'John',
					'Matt',
					'Doe',
				],
				Helper::unsetArrayEmptyValues([
					'John',
					'',
					'Matt',
					null,
					'Doe',
				])
			);
		}

		public function testGetValueFromArrByKey(): void {
			$this->assertEquals(
				'',
				Helper::getValueFromArrByKey(null)
			);

			$this->assertEquals(
				'',
				Helper::getValueFromArrByKey([])
			);

			$this->assertEquals(
				'',
				Helper::getValueFromArrByKey([
					'key1' => 'Key 1',
				], 'key2')
			);

			$this->assertEquals(
				'Key 2',
				Helper::getValueFromArrByKey([
					'key1' => 'Key 1',
					'key2' => 'Key 2',
				], 'key2')
			);
		}

		public function testGenerateKeyValueStringFromArray(): void {
			$this->assertEquals(
				'',
				Helper::generateKeyValueStringFromArray(null)
			);

			$this->assertEquals(
				'',
				Helper::generateKeyValueStringFromArray([])
			);

			$this->assertEquals(
				'type="text" name="test-input" id="test-input" placeholder="Test Input"',
				Helper::generateKeyValueStringFromArray([
					'type' => 'text',
					'name' => 'test-input',
					'id' => 'test-input',
					'placeholder' => 'Test Input',
				])
			);

			$this->assertEquals(
				'pre_type="text" pre_name="test-input" pre_id="test-input" pre_placeholder="Test Input"',
				Helper::generateKeyValueStringFromArray([
					'type' => 'text',
					'name' => 'test-input',
					'id' => 'test-input',
					'placeholder' => 'Test Input',
				], 'pre_')
			);

			$this->assertEquals(
				'type:"text" name:"test-input" id:"test-input" placeholder:"Test Input"',
				Helper::generateKeyValueStringFromArray([
					'type' => 'text',
					'name' => 'test-input',
					'id' => 'test-input',
					'placeholder' => 'Test Input',
				], '', ':')
			);

			$this->assertEquals(
				'type=-text- name=-test-input- id=-test-input- placeholder=-Test Input-',
				Helper::generateKeyValueStringFromArray([
					'type' => 'text',
					'name' => 'test-input',
					'id' => 'test-input',
					'placeholder' => 'Test Input',
				], '', '=', '-')
			);

			$this->assertEquals(
				'type="text"_join_name="test-input"_join_id="test-input"_join_placeholder="Test Input"',
				Helper::generateKeyValueStringFromArray([
					'type' => 'text',
					'name' => 'test-input',
					'id' => 'test-input',
					'placeholder' => 'Test Input',
				], '', '=', '"', '_join_')
			);
		}

		public function testGenerateUrlParamsFromArray(): void {
			$this->assertEquals(
				'k1=page1&k2=page2&k3=page3&k4=page4',
				Helper::generateUrlParamsFromArray([
					'k1' => 'page1',
					'k2' => 'page2',
					'k3' => 'page3',
					'k4' => 'page4',
				])
			);
		}

		public function testRemoveMultipleSlashes(): void {
			$this->assertEquals('http://google.com/test', Helper::removeMultipleSlashes('http://google.com//test'));
			$this->assertEquals('https://google.com/test', Helper::removeMultipleSlashes('https://google.com/////test'));
			$this->assertEquals('/dgsplash/public_html/src/website/', Helper::removeMultipleSlashes('//dgsplash/public_html////src/website//'));
		}

		public function testFolderExists(): void {
			$this->assertFalse(Helper::folderExists(null));
			$this->assertFalse(Helper::folderExists(''));
			$this->assertFalse(Helper::folderExists('Tests', __DIR__ . '/'));
			$this->assertTrue(Helper::folderExists('PhpUnits', dirname(__DIR__, 2)));
			$this->assertTrue(Helper::folderExists('src', dirname(__DIR__, 3)));
			$this->assertTrue(Helper::folderExists('PhpUnits', dirname(__DIR__, 3), true));
		}

		public function testCreateFolderDeleteFolder(): void {
			$this->assertFalse(Helper::folderExists('NewFolder', TEST_UPLOAD_DIR));
			$this->assertTrue(Helper::createFolder(TEST_UPLOAD_DIR . 'NewFolder'));

			$this->assertFalse(Helper::createFolder(TEST_UPLOAD_DIR . 'NewFolder'));
			$this->assertTrue(Helper::folderExists('NewFolder', TEST_UPLOAD_DIR));
			Helper::deleteFileOrFolder(TEST_UPLOAD_DIR . 'NewFolder');
		}

		public function testCreateFoldersRecursive(): void {
			$this->assertFalse(Helper::folderExists('Test1', TEST_UPLOAD_DIR));
			$this->assertFalse(Helper::folderExists('Test2', TEST_UPLOAD_DIR . 'Test1/'));
			$this->assertFalse(Helper::folderExists('Test3', TEST_UPLOAD_DIR . 'Test1/Test2/'));

			$this->assertTrue(Helper::createFolder(TEST_UPLOAD_DIR . 'Test1/Test2/Test3'));

			$this->assertTrue(Helper::folderExists('Test1', TEST_UPLOAD_DIR));
			$this->assertTrue(Helper::folderExists('Test2', TEST_UPLOAD_DIR . 'Test1/'));
			$this->assertTrue(Helper::folderExists('Test3', TEST_UPLOAD_DIR . 'Test1/Test2/'));

			Helper::deleteFileOrFolder(TEST_UPLOAD_DIR . 'Test1/Test2/Test3');
			Helper::deleteFileOrFolder(TEST_UPLOAD_DIR . 'Test1/Test2');
			Helper::deleteFileOrFolder(TEST_UPLOAD_DIR . 'Test1');
		}

		public function testDeleteFile(): void {
			$newFile = TEST_UPLOAD_DIR . 'unit-test.txt';

			$this->assertFalse(file_exists($newFile));
			copy(TEST_COMMON_DIR . 'test1.txt', $newFile);

			$this->assertTrue(file_exists($newFile));
			$this->assertTrue(Helper::deleteFileOrFolder($newFile));

			$this->assertFalse(file_exists($newFile));
		}

		public function testGetAllFiles(): void {
			$dir = dirname(__DIR__, 2) . '/_common/recursive';

			$this->assertEqualsCanonicalizing([
				$dir . '/file1.html',
				$dir . '/file2.html',
			], Helper::getAllFiles($dir));

			$this->assertEqualsCanonicalizing(
				[
					$dir . '/file1.html',
					$dir . '/file2.html',
					$dir . '/folder-1/file1.html',
					$dir . '/folder-1/file2.html',
					$dir . '/folder-1/folder-1.1/file.html',
					$dir . '/folder-1/folder-1.1/folder-1.1.1/file.html',
					$dir . '/folder-1/folder-1.2/file.html',
					$dir . '/folder-2/file1.html',
					$dir . '/folder-2/file2.html',
					$dir . '/folder-2/folder-2.1/file.html',
					$dir . '/folder-2/folder-2.2/file.html',
				],
				Helper::getAllFiles($dir, true)
			);
		}

		public function testGetAllFoldersSuccess(): void {
			$dir = dirname(__DIR__, 2) . '/_common/recursive';

			$this->assertEqualsCanonicalizing(
				[
					$dir . '/folder-1',
					$dir . '/folder-2',
				],
				Helper::getAllFolders($dir)
			);

			$this->assertEqualsCanonicalizing(
				[
					$dir . '/folder-1',
					$dir . '/folder-2',
					$dir . '/folder-1/folder-1.1',
					$dir . '/folder-1/folder-1.2',
					$dir . '/folder-2/folder-2.1',
					$dir . '/folder-2/folder-2.2',
					$dir . '/folder-1/folder-1.1/folder-1.1.1',
				],
				Helper::getAllFolders($dir, true)
			);
		}

		public function testGetYoutubeId(): void {
			$this->assertEquals('', Helper::getYoutubeId(null));
			$this->assertEquals('', Helper::getYoutubeId(''));
			$this->assertEquals('', Helper::GetYoutubeId('https://www.youtube.com/embed/123456789'));
			$this->assertEquals('IZbN_nmxAGk', Helper::GetYoutubeId('https://www.youtube.com/embed/IZbN_nmxAGk'));
		}

		public function testEncryptLink(): void {
			$link = 'https://john-doe.com/projects?page=2&category=2';

			$this->assertEquals('', Helper::encryptLink(null));
			$this->assertEquals('', Helper::encryptLink(''));
			$this->assertEquals(str_replace('&', '[amp;]', base64_encode($link)), Helper::encryptLink($link));
		}

		public function testDecryptLink(): void {
			$link = 'https://john-doe.com/projects?page=2&category=2';

			$this->assertEquals('', Helper::decryptLink(null));
			$this->assertEquals('', Helper::decryptLink(''));
			$this->assertEquals($link, Helper::decryptLink(str_replace('&', '[amp;]', base64_encode($link))));
		}

		public static function getStatusFromCodeProvider(): array {
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
		 * @dataProvider getStatusFromCodeProvider
		 */
		public function testGetStatusFromCode(int $givenCode, string $expectedStatus): void {
			$this->assertEquals($expectedStatus, Helper::getStatusFromCode($givenCode));
		}

		/**
		 * @dataProvider getContentFromFileEmptyParamThrowsProvider
		 */
		public function testGetContentFromFileEmptyParamThrows($filePath): void {
			$this->expectException(NotEmptyParamException::class);
			Helper::getContentFromFile($filePath);
		}

		public static function getContentFromFileEmptyParamThrowsProvider(): array {
			return [
				'null' => [
					'filePath' => null,
				],
				'empty' => [
					'filePath' => '',
				],
			];
		}

		public function testGetContentFromFileNotFoundThrows(): void {
			$this->expectException(FileNotFoundException::class);
			Helper::getContentFromFile(__DIR__ . '/random-file.html');
		}

		public function testGetContentFromFileWithoutReplaceSuccess(): void {
			$this->assertEquals(
				'<h1>This is Test File 1</h1>',
				Helper::getContentFromFile(dirname(__DIR__, 2) . '/_common/test1.html')
			);
		}

		public function testGetContentFromFileWithReplaceSuccess(): void {
			$this->assertEquals(
				'This was a test file!',
				Helper::getContentFromFile(dirname(__DIR__, 2) . '/_common/test1.txt', [
					'This is a test file!' => 'This was a test file!',
				])
			);
		}

		public function testGetJsonContentFromFileAsArraySuccess(): void {
			$this->assertEquals(
				[
					'fullName' => [
						'firstName' => 'John',
						'middleName' => 'Matt',
						'lastName' => 'Doe',
					],
					'position' => 'Senior Software Engineer',
					'languages' => [
						'Arabic',
						'English',
						'French',
						'Spanish',
					],
				],
				Helper::getJsonContentFromFileAsArray(dirname(__DIR__, 2) . '/_common/test1.json')
			);
		}

		public function testConvertMultidimensionalArrayToSingleDimensional(): void {
			$this->assertEquals([
				'name.first' => 'John',
				'name.middle' => 'Matt',
				'name.last' => 'Doe',
				'address.building' => 'Bldg',
				'address.street' => 'Street',
				'address.region' => 'Region',
				'address.country' => 'Lebanon',
				'contact.info.mobile' => '+961111111',
				'contact.info.email' => 'email@test.com',
			], Helper::convertMultidimensionalArrayToSingleDimensional([
				'name' => [
					'first' => 'John',
					'middle' => 'Matt',
					'last' => 'Doe',
				],
				'address' => [
					'building' => 'Bldg',
					'street' => 'Street',
					'region' => 'Region',
					'country' => 'Lebanon',
				],
				'contact' => [
					'info' => [
						'mobile' => '+961111111',
						'email' => 'email@test.com',
					],
				],
			]));
		}

		public function testConvertSingleDimensionalArrayToMultidimensional(): void {
			$this->assertEquals([
				'name' => [
					'first' => 'John',
					'middle' => 'Matt',
					'last' => 'Doe',
				],
				'address' => [
					'building' => 'Bldg',
					'street' => 'Street',
					'region' => 'Region',
					'country' => 'Lebanon',
				],
				'contact' => [
					'info' => [
						'mobile' => '+961111111',
						'email' => 'email@test.com',
					],
				],
			], Helper::convertSingleDimensionalArrayToMultidimensional([
				'name.first' => 'John',
				'name.middle' => 'Matt',
				'name.last' => 'Doe',
				'address.building' => 'Bldg',
				'address.street' => 'Street',
				'address.region' => 'Region',
				'address.country' => 'Lebanon',
				'contact.info.mobile' => '+961111111',
				'contact.info.email' => 'email@test.com',
			]));
		}

		public function testAddPrefixToArrayKeys(): void {
			$this->assertEquals([
				'pre_name' => 'John',
				'pre_middle' => 'Matt',
				'pre_last' => 'Doe',
			], Helper::addPrefixToArrayKeys([
				'name' => 'John',
				'middle' => 'Matt',
				'last' => 'Doe',
			], 'pre_'));
		}

//		public function testGenerateFullUrlSuccess() {
//			$this->assertEquals(
//				"home",
//				Helper::GenerateFullUrl("home")
//			);
//
//			$this->assertEquals(
//				"home/en",
//				Helper::GenerateFullUrl("home", Lang::EN)
//			);
//
//			$this->assertEquals(
//				"home?lang=en",
//				Helper::GenerateFullUrl("home", Lang::EN, [], [], '', false)
//			);
//
//			$this->assertEquals(
//				"products/en/product-001",
//				Helper::GenerateFullUrl("products", Lang::EN, [
//					"key" => "product-001",
//				])
//			);
//
//			$this->assertEquals(
//				"products?lang=en&key=product-001",
//				Helper::GenerateFullUrl("products", Lang::EN, [
//					"key" => "product-001",
//				], [], '', false)
//			);
//
//			$this->assertEquals(
//				"products/en/product-001?filter=active",
//				Helper::GenerateFullUrl("products", Lang::EN, [
//					"key" => "product-001",
//				], [
//					"filter" => "active",
//				])
//			);
//
//			$this->assertEquals(
//				"products?lang=en&key=product-001&filter=active",
//				Helper::GenerateFullUrl("products", Lang::EN, [
//					"key" => "product-001",
//				], [
//					"filter" => "active",
//				], '', false)
//			);
//
//			$this->assertEquals(
//				"products/en/product-001?filter=active&categories%5B%5D=category-01&categories%5B%5D=category-02&categories%5B%5D=category-03",
//				Helper::GenerateFullUrl("products", Lang::EN, [
//					"key" => "product-001",
//				], [
//					"filter" => "active",
//					"categories" => [
//						"category-01",
//						"category-02",
//						"category-03",
//					],
//				])
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com/home/en",
//				Helper::GenerateFullUrl("home", Lang::EN, [], [], "https://john-doe.com/")
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com/home/en",
//				Helper::GenerateFullUrl("home", Lang::EN, [], [], "https://john-doe.com")
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com/home/en",
//				Helper::GenerateFullUrl("home", Lang::EN, [], [], "https://john-doe.com////")
//			);
//
//			$this->assertEquals(
//				"www.john-doe.com/home/en",
//				Helper::GenerateFullUrl("home", Lang::EN, [], [], "www.john-doe.com////")
//			);
//		}
//
//		public function testAddVersionParameterToPathSuccess() {
//			$this->assertEquals(
//				"assets/css/styles.css",
//				Helper::AddVersionParameterToPath("assets/css/styles.css", '')
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com/assets/css/styles.css",
//				Helper::AddVersionParameterToPath("assets/css/styles.css", "https://john-doe.com")
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com/assets/css/styles.css?v=1.0",
//				Helper::AddVersionParameterToPath("assets/css/styles.css", "https://john-doe.com", "1.0")
//			);
//		}
//
//		public function testGetAllFilesSuccess() {
//			$this->markTestSkipped('This test is skipped because it is working on its own but not in bulk.');
//			$dir = str_replace("\PhpUnits\Helpers", "\_CommonFiles\Recursive", __DIR__);
//
//			$this->assertEqualsCanonicalizing(
//				[
//					$dir . "/file1.html",
//					$dir . "/file2.html",
//				],
//				Helper::GetAllFiles($dir, false)
//			);
//
//			$this->assertEqualsCanonicalizing(
//				[
//					$dir . "/file1.html",
//					$dir . "/file2.html",
//					$dir . "/Folder1/file1.html",
//					$dir . "/Folder1/file2.html",
//					$dir . "/Folder1/Folder1-1/file.html",
//					$dir . "/Folder1/Folder1-1/Folder1-1-1/file.html",
//					$dir . "/Folder1/Folder1-2/file.html",
//					$dir . "/Folder2/file1.html",
//					$dir . "/Folder2/file2.html",
//					$dir . "/Folder2/Folder2-1/file.html",
//					$dir . "/Folder2/Folder2-2/file.html",
//				],
//				Helper::GetAllFiles($dir, true)
//			);
//		}
//
//		public function testGetAllFoldersSuccess(): void {
//			$this->markTestSkipped('This test is skipped because it is working on its own but not in bulk.');
//			$dir = str_replace("\PhpUnits\Helpers", "\_CommonFiles\Recursive", __DIR__);
//
//			$this->assertEqualsCanonicalizing(
//				[
//					$dir . "/Folder1",
//					$dir . "/Folder2",
//				],
//				Helper::GetAllFolders($dir, false)
//			);
//
//			$this->assertEqualsCanonicalizing(
//				[
//					$dir . "/Folder1",
//					$dir . "/Folder2",
//					$dir . "/Folder1/Folder1-1",
//					$dir . "/Folder1/Folder1-2",
//					$dir . "/Folder2/Folder2-1",
//					$dir . "/Folder2/Folder2-2",
//					$dir . "/Folder1/Folder1-1/Folder1-1-1",
//				],
//				Helper::GetAllFolders($dir, true)
//			);
//		}
//
//		public function testDeleteFoldersAndFilesSuccess(): void {
//			$this->markTestSkipped('This test is skipped because it is working on its own but not in bulk.');
//			$dir = str_replace("\PhpUnits\Helpers", "\_CommonFiles", __DIR__);
//			$testDir = $dir . "/TestDeleteFoldersAndFiles";
//
//			$folders = [
//				$testDir . "/Folder1",
//				$testDir . "/Folder2",
//				$testDir . "/Folder1/Folder1-1",
//				$testDir . "/Folder1/Folder1-2",
//				$testDir . "/Folder2/Folder2-1",
//			];
//
//			$files = [
//				$testDir . "/file1.html",
//				$testDir . "/file2.html",
//				$testDir . "/Folder1/file.html",
//				$testDir . "/Folder1/Folder1-1/file.html",
//				$testDir . "/Folder1/Folder1-2/file.html",
//				$testDir . "/Folder2/file.html",
//				$testDir . "/Folder2/Folder2-1/file.html",
//			];
//
//			foreach ($folders as $folder) {
//				Helper::CreateFolderRecursive($folder);
//			}
//
//			foreach ($files as $file) {
//				copy(
//					$dir . "/Recursive/file1.html",
//					$file
//				);
//			}
//
//			$allFolders = Helper::GetAllFolders($testDir, true);
//			$allFiles = Helper::GetAllFiles($testDir, true);
//
//			$this->assertEqualsCanonicalizing($folders, $allFolders);
//			$this->assertEqualsCanonicalizing($files, $allFiles);
//
//			Helper::DeleteFoldersAndFiles($testDir, false);
//
//			$allFolders = Helper::GetAllFolders($testDir, true);
//			$allFiles = Helper::GetAllFiles($testDir, true);
//
//			$this->assertEmpty($allFolders);
//			$this->assertEmpty($allFiles);
//			$this->assertDirectoryExists($testDir);
//
//			Helper::DeleteFoldersAndFiles($testDir, true);
//
//			$this->assertDirectoryDoesNotExist($testDir);
//		}
//

//
//		public function testAddSchemeIfMissingSuccess() {
//			$this->assertEquals(
//				'',
//				Helper::AddSchemeIfMissing('', '')
//			);
//
//			$this->assertEquals(
//				"john-doe.com",
//				Helper::AddSchemeIfMissing("john-doe.com", '')
//			);
//
//			$this->assertEquals(
//				"http://john-doe.com",
//				Helper::AddSchemeIfMissing("http://john-doe.com", "https://")
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com",
//				Helper::AddSchemeIfMissing("https://john-doe.com", "http://")
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com",
//				Helper::AddSchemeIfMissing("john-doe.com", "https")
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com",
//				Helper::AddSchemeIfMissing("john-doe.com", "https://")
//			);
//		}
//
//		public function testReplaceSchemeSuccess() {
//			$this->assertEquals(
//				'',
//				Helper::ReplaceScheme('', '')
//			);
//
//			$this->assertEquals(
//				"john-doe.com",
//				Helper::ReplaceScheme("john-doe.com", '')
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com",
//				Helper::ReplaceScheme("http://john-doe.com", "https://")
//			);
//
//			$this->assertEquals(
//				"http://john-doe.com",
//				Helper::ReplaceScheme("https://john-doe.com", "http://")
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com",
//				Helper::ReplaceScheme("john-doe.com", "https")
//			);
//
//			$this->assertEquals(
//				"https://john-doe.com",
//				Helper::ReplaceScheme("john-doe.com", "https://")
//			);
//		}
//
//		public function testIsValidUrl() {
//			$this->assertFalse(
//				Helper::IsValidUrl("John Doe")
//			);
//
//			$this->assertFalse(
//				Helper::IsValidUrl("John Doe: https://john-doe.com")
//			);
//
//			$this->assertFalse(
//				Helper::IsValidUrl("http//john-doe.com")
//			);
//
//			$this->assertFalse(
//				Helper::IsValidUrl("http:/john-doe.com")
//			);
//
//			$this->assertFalse(
//				Helper::IsValidUrl("http:john-doe.com")
//			);
//
//			$this->assertFalse(
//				Helper::IsValidUrl("https:/john-doe.com")
//			);
//
//			$this->assertTrue( //To be fixed!
//				Helper::IsValidUrl("http://john-doe.com John Doe")
//			);
//
//			$this->assertTrue(
//				Helper::IsValidUrl("http://john-doe.com")
//			);
//
//			$this->assertTrue(
//				Helper::IsValidUrl("https://john-doe.com")
//			);
//		}
//
//		/**
//		 * @dataProvider MissingParamsProvider
//		 */
//		public function testMissingParamsSuccess(array $params, array $required, array $expected): void {
//			$missing = Helper::MissingParams($params, $required);
//			$this->assertEqualsCanonicalizing($expected, $missing);
//		}
//
//		public function MissingParamsProvider(): array {
//			return [
//				'no_missing' => [
//					'params' => [
//						'first_name' => 'Jon',
//						'last_name' => '',
//						'age' => '29',
//					],
//					'required' => ['first_name', 'last_name', 'age'],
//					'expected' => [
//						'missing' => [],
//						'found' => ['first_name', 'last_name', 'age'],
//					],
//				],
//				'some_missing' => [
//					'params' => [
//						'first_name' => 'Jon',
//						'age' => '29',
//					],
//					'required' => ['first_name', 'last_name', 'age'],
//					'expected' => [
//						'missing' => ['last_name'],
//						'found' => ['first_name', 'age'],
//					],
//				],
//				'all_missing' => [
//					'params' => [
//						'test_param' => 'Test',
//					],
//					'required' => ['first_name', 'last_name', 'age'],
//					'expected' => [
//						'missing' => ['first_name', 'last_name', 'age'],
//						'found' => [],
//					],
//				],
//			];
//		}
//
//		/**
//		 * @dataProvider missingParamsThrowsSuccessProvider
//		 */
//		public function testMissingParamsThrowsSuccess(
//			array  $params,
//			array  $required,
//			string $exception,
//			string $exceptionMessage,
//		): void {
//			if (!empty($exception)) {
//				$this->expectException($exception);
//				$this->expectExceptionMessage($exceptionMessage);
//			}
//
//			Helper::MissingParamsThrows($params, $required);
//
//			if (empty($exception)) {
//				$this->assertTrue(true);
//			}
//		}
//
//		public function missingParamsThrowsSuccessProvider(): array {
//			return [
//				'throws' => [
//					'params' => [
//						'first_name' => 'Jon',
//						'last_name' => '',
//						'age' => '29',
//					],
//					'required' => ['first_name', 'last_name', 'age', 'country'],
//					'exception' => MissingParamsException::class,
//					'exceptionMessage' => 'Missing Parameter(s): `country`',
//				],
//				'not_throws' => [
//					'params' => [
//						'first_name' => 'Jon',
//						'last_name' => 'Doe',
//						'age' => '29',
//					],
//					'required' => ['first_name', 'last_name', 'age'],
//					'exception' => '',
//					'exceptionMessage' => '',
//				],
//			];
//		}
//
//		public function testMissingNotEmptyParamsSuccess(): void {
//			$params = [
//				'first_name' => 'Jon',
//				'last_name' => '',
//				'age' => '29',
//			];
//			$required = ['first_name', 'last_name', 'age', 'country'];
//			$expected = [
//				'missing' => ['last_name', 'country'],
//				'found' => ['first_name', 'age'],
//			];
//
//			$missing = Helper::MissingNotEmptyParams($params, $required);
//
//			$this->assertEqualsCanonicalizing($expected['missing'], $missing['missing']);
//			$this->assertEqualsCanonicalizing($expected['found'], $missing['found']);
//		}
//
//		/**
//		 * @dataProvider missingNotEmptyParamsThrowsSuccessProvider
//		 */
//		public function testMissingNotEmptyParamsThrowsSuccess(
//			array  $params,
//			array  $required,
//			string $exception,
//			string $exceptionMessage,
//		): void {
//			if (!empty($exception)) {
//				$this->expectException($exception);
//				$this->expectExceptionMessage($exceptionMessage);
//			}
//
//			Helper::MissingNotEmptyParamsThrows($params, $required);
//
//			if (empty($exception)) {
//				$this->assertTrue(true);
//			}
//		}
//
//		public function missingNotEmptyParamsThrowsSuccessProvider(): array {
//			return [
//				'throws' => [
//					'params' => [
//						'first_name' => 'Jon',
//						'last_name' => '',
//						'age' => '29',
//					],
//					'required' => ['first_name', 'last_name', 'age', 'country'],
//					'exception' => MissingParamsException::class,
//					'exceptionMessage' => 'Missing Parameter(s): `country`, `last_name`',
//				],
//				'not_throws' => [
//					'params' => [
//						'first_name' => 'Jon',
//						'last_name' => 'Doe',
//						'age' => '29',
//					],
//					'required' => ['first_name', 'last_name', 'age'],
//					'exception' => '',
//					'exceptionMessage' => '',
//				],
//			];
//		}
	}
