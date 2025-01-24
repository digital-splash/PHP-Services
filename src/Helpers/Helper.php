<?php

	namespace DigitalSplash\Helpers;

	use DigitalSplash\Language\Models\Language;
	use DigitalSplash\Models\Code;
	use DigitalSplash\Models\HttpCode;
	use DigitalSplash\Models\Status;

	class Helper {
		/**
		 * Cleans a String by removing additional spaces and slashes
		 */
		public static function cleanString(string $var): string {
			return trim(
				stripslashes(
					$var
				)
			);
		}

		/**
		 * Converts HTML tags in a string to be visible, and vice versa
		 */
		public static function cleanHtmlText(string $var, bool $convertSpecialChars = true): string {
			$var = self::CleanString($var);

			if ($convertSpecialChars) {
				$var = htmlspecialchars($var, ENT_QUOTES);
			}

			return $var;
		}

		/**
		 * Get the string value safely
		 */
		public static function getStringSafe(?string $str): string {
			if (self::isNullOrEmpty($str)) {
				return '';
			}

			return $str;
		}

		/**
		 * Check if a value is null or empty
		 */
		public static function isNullOrEmpty($var): bool {
			return empty($var);
		}

		/**
		 * Converts a value to an integer
		 */
		public static function convertToInt($var): int {
			if (!self::isNullOrEmpty($var) && is_numeric($var)) {
				return (int) round($var);
			}

			return 0;
		}

		/**
		 * Converts a value to a decimal
		 */
		public static function convertToDec($var, int $decimalPlaces = 2): float {
			if (!self::isNullOrEmpty($var)) {
				$var = round((float) $var, $decimalPlaces);
				if (is_numeric($var) && !is_nan($var)) {
					return $var;
				}
			}
			return 0;
		}

		/**
		 * Converts a value to a decimal and returns as a String
		 */
		public static function convertToDecAsString($var, int $decimalPlaces = 2): string {
			return number_format(self::ConvertToDec($var, $decimalPlaces), $decimalPlaces);
		}

		/**
		 * Converts a value to a boolean
		 */
		public static function convertToBool($var): bool {
			switch (gettype($var)) {
				case 'boolean':
					$ret = $var;
					break;
				case 'string':
					$var = trim($var);
					$ret = true;
					if (self::isNullOrEmpty($var) || $var === 'false') {
						$ret = false;
					}
					break;

				case 'integer':
				case 'double':
					$var = Helper::ConvertToDec($var);
					$ret = $var > 0;
					break;
				default:
					$ret = (bool) $var;
			}

			return $ret;
		}

		/**
		 * Encrypt a String
		 */
		public static function encryptString(string $var): string {
			return hash('sha512', self::cleanString($var));
		}

		/**
		 * Check if a string is Encrypted
		 */
		public static function isEncrypted(string $var): bool {
			return preg_match('/[0-9a-f]{64}/i', $var);
		}

		/**
		 * Generate a Random String
		 */
		public static function generateRandomString(int $length = 8, bool $hasInt = true, bool $hasString = false, bool $hasSymbols = false, string $lang = Language::EN): string {
			$possible = '';

			if ($lang !== Language::AR) {
				if ($hasInt) {
					$possible .= '0123456789';
				}
				if ($hasString) {
					$possible .= 'abcdefghijklmnopqrstuvwxyz';
					$possible .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				}
			}

			if ($lang === Language::AR || $lang === Language::ALL) {
				if ($hasInt) {
					$possible .= '٠١٢٣٤٥٦٧٨٩';
				}
				if ($hasString) {
					$possible .= 'ضصثقفغعهخحجدشسيبلاتنمكطئءؤرلاىةوزظ';
				}
			}

			if ($hasSymbols) {
				$possible .= '!@#$%^&*()_-+=?\/|`~.,<>';
			}

			$var = '';
			if (!self::isNullOrEmpty($possible)) {
				/* Add random characters to $key until $length is reached */

				for ($i = 0; $i < $length; $i++) {
					$minRandNb = 0;
					$maxRandNb = strlen($possible) - 1;
					$rand = mt_rand($minRandNb, $maxRandNb);

					/* Pick a random character from the possible ones */
					$char = substr($possible, $rand, 1);

					$var .= $char;
				}
			}

			return $var;
		}

		/**
		 * Removes all the slashes from a string
		 */
		public static function removeSlashes(string $var): string {
			return stripslashes(trim(implode('', explode('\\', $var))));
		}

		/**
		 * Removes all the spaces from a string
		 */
		public static function removeSpaces(string $var): string {
			return str_replace(' ', '', trim($var));
		}

		/**
		 * Limit a text to a fixed number of characters
		 */
		public static function truncateStr(string $text, int $nbOfChar, string $extension = '...', string $lang = Language::EN, bool $cleanStr = true): string {
			if ($lang === Language::AR) {
				$nbOfChar = ceil($nbOfChar * 1.8);
			}

			if ($cleanStr) {
				$text = self::cleanString($text);
			}

			if (strlen($text) > $nbOfChar) {
				$text = substr($text, 0, $nbOfChar) . $extension;
			}

			return $text;
		}

		/**
		 * Search if is a string begins with a defined characters combination
		 */
		public static function stringBeginsWith(string $string, $search): bool {
			if (self::isNullOrEmpty($search)) {
				return false;
			}

			if (is_array($search)) {
				foreach ($search as $s) {
					if ((strncmp($string, $s, strlen($s)) === 0)) {
						return true;
					}
				}
				return false;
			}

			return strncmp($string, $search, strlen($search)) === 0;
		}

		/**
		 * Search if is a string ends with a defined characters combination
		 */
		public static function stringEndsWith(string $string, $search): bool {
			if (self::isNullOrEmpty($search)) {
				return false;
			}

			if (is_array($search)) {
				foreach ($search as $s) {
					if (substr($string, (strlen($string) - strlen($s))) === $s) {
						return true;
					}
				}
				return false;
			}

			return substr($string, (strlen($string) - strlen($search))) === $search;
		}

		/**
		 * Search if is a string contains a value
		 */
		public static function stringHasChar(string $string, $search): bool {
			if (self::isNullOrEmpty($search)) {
				return false;
			}

			if (is_array($search)) {
				foreach ($search as $searchKey) {
					if (strpos($string, $searchKey) !== false) {
						return true;
					}
				}
				return false;
			}

			return strpos($string, $search) !== false;
		}

		/**
		 * Search for the allowed tags in the content and display them
		 */
		public static function stripHtml(string $content, $allowedTags = null): string {
			return strip_tags($content, $allowedTags);
		}

		/**
		 * Replace all values in a text
		 */
		public static function textReplace(string $text, array $params = []): string {
			return str_replace(
				array_keys($params),
				array_values($params),
				$text
			);
		}

		/**
		 * Separate Camel Case String
		 */
		public static function splitCamelcaseString(string $str, string $split = ' '): string {
			$pieces = preg_split('/(?=[A-Z])/', $str);
			return trim(implode($split, $pieces));
		}

		/**
		 * Converts a String into Camel Case
		 */
		public static function convertStringToCamelcase(string $str, array $separators = ['-', '_']): string {
			return str_replace(
				' ',
				'',
				ucwords(
					strtolower(
						str_replace(
							$separators,
							' ',
							$str
						)
					)
				)
			);
		}

		/**
		 * Checks if the given str contains any arabic characters
		 */
		public static function hasArabicChar(string $str): bool {
			if (mb_detect_encoding($str) !== 'UTF-8') {
				$str = mb_convert_encoding($str, mb_detect_encoding($str), 'UTF-8');
			}

			/*
			$str = str_split($str); <- this function is not mb safe, it splits by bytes, not characters. we cannot use it
			$str = preg_split('//u',$str); <- this function would probably work fine but there was a bug reported in some php version so it pslits by bytes and not chars as well
			*/

			preg_match_all('/.|\n/u', $str, $matches);
			$chars = $matches[0];
			$arabic_count = 0;

			foreach ($chars as $char) {
				/* BEGIN: I just copied this function from the php.net comments, but it should work fine! */
				$k = mb_convert_encoding($char, 'UCS-2LE', 'UTF-8');
				$k1 = ord(substr($k, 0, 1));
				$k2 = ord(substr($k, 1, 1));

				$pos = $k2 * 256 + $k1;
				/* END: I just copied this function from the php.net comments, but it should work fine! */

				if ($pos >= 1536 && $pos <= 1791) {
					$arabic_count++;
				}
			}

			return $arabic_count > 0;
		}

		/**
		 * Converts the given string into a safe one | Supports English & Arabic
		 */
		public static function safeName(string $str, string $trimChar = '-'): string {
			$friendlyURL = htmlentities($str, ENT_COMPAT, 'UTF-8', false);
			$friendlyURL = preg_replace('/&([a-z]{1,2})(?:acute|lig|grave|ring|tilde|uml|cedil|caron);/i', '\1', $friendlyURL);
			$friendlyURL = html_entity_decode($friendlyURL, ENT_COMPAT, 'UTF-8');
			$friendlyURL = preg_replace('/[^أ-يa-zA-Z0-9٠-٩_.-]/u', $trimChar, $friendlyURL);
			$friendlyURL = preg_replace('/-+/', $trimChar, $friendlyURL);
			$friendlyURL = trim($friendlyURL, $trimChar);

			$isArabic = self::hasArabicChar($str);
			if (!$isArabic) {
				$friendlyURL = strtolower($friendlyURL);
			}

			return $friendlyURL;
		}

		/**
		 * Converts the given String into an Array
		 */
		public static function explode(string $separator = '', ?string $string = '', int $chunkLength = 0): array {
			if (self::isNullOrEmpty($string)) {
				return [];
			}

			if (!self::isNullOrEmpty($separator)) {
				return explode($separator, $string);
			}

			if ($chunkLength > 0) {
				$arr = [];
				while (strlen($string) > $chunkLength) {
					$chunk = substr($string, 0, $chunkLength);

					$arr[] = $chunk;
					$string = substr($string, $chunkLength);
				}
				if (strlen($string) > 0) {
					$arr[] = $string;
				}
				return $arr;
			}

			return [$string];
		}

		/**
		 * Returns a [delimiter] seperated string from the values inside the given array
		 */
		public static function implode(string $separator = ' ', ?array $array = []): string {
			if (self::isNullOrEmpty($array)) {
				return '';
			}

			return implode($separator, self::unsetArrayEmptyValues($array));
		}

		/**
		 * Unset Empty Values from the given object/array
		 */
		public static function unsetArrayEmptyValues(?array $array): array {
			if (self::isNullOrEmpty($array)) {
				return [];
			}

			return array_values(
				array_filter(
					$array,
					function($value) {
						if (!self::isNullOrEmpty($value)) {
							return $value;
						}
					}
				)
			);
		}

		/**
		 * Get the value of the given key in a given array
		 */
		public static function getValueFromArrByKey(?array $arr, string $key = ''): string {
			if (self::isNullOrEmpty($arr) || !isset($arr[$key])) {
				return '';
			}

			return $arr[$key];
		}

		/**
		 * Generate Key Value String from Array
		 */
		public static function generateKeyValueStringFromArray(?array $params, string $keyPrefix = '', string $keyValueJoin = '=', string $valueHolder = '"', string $elemsJoin = ' '): string {
			if (self::isNullOrEmpty($params)) {
				return '';
			}

			$str = [];
			foreach ($params as $k => $v) {
				$k = $keyPrefix . $k;
				$str[] = $k . $keyValueJoin . $valueHolder . $v . $valueHolder;
			}

			return self::implode($elemsJoin, $str);
		}

		/**
		 * Remove multiple slashed from the given string
		 */
		public static function removeMultipleSlashes(string $var): string {
			$urlScheme = '';
			if (self::stringBeginsWith($var, 'http://')) {
				$urlScheme = 'http://';
			}
			if (self::stringBeginsWith($var, 'https://')) {
				$urlScheme = 'https://';
			}

			$var = str_replace($urlScheme, '', $var);

			while (strpos($var, '//') !== false) {
				$var = str_replace('//', '/', $var);
			}

			return $urlScheme . $var;
		}

		/**
		 * Checks if the given folder is available in the given directory
		 */
		public static function folderExists(?string $dirName, string $path = './', bool $checkSubFolders = false): bool {
			if (self::isNullOrEmpty($dirName)) {
				return false;
			}

			$path = self::removeMultipleSlashes($path . '/');
			if (is_dir($path . $dirName)) {
				return true;
			}

			if ($checkSubFolders) {
				$tree = glob($path . '*', GLOB_ONLYDIR);
				if ($tree && count($tree) > 0) {
					foreach ($tree as $dir) {
						if (self::folderExists($dirName, $dir)) {
							return true;
						}
					}
				}
			}

			return false;
		}

		/**
		 * Create the given folder
		 */
		public static function createFolder(string $dir, string $permission = '0777', bool $recursive = true): bool {
			if (!is_dir($dir)) {
				return mkdir($dir, octdec($permission), $recursive);
			}

			return false;
		}

		/**
		 * Delete the given file/folder
		 */
		public static function deleteFileOrFolder(string $dir): bool {
			if (file_exists($dir)) {
				if (is_dir($dir)) {
					return rmdir($dir);
				} else {
					return unlink($dir);
				}
			}

			return false;
		}

		/**
		 * Retrieve YouTube embed id from the video full link
		 */
		public static function getYoutubeId(?string $url): string {
			if (self::isNullOrEmpty($url)) {
				return '';
			}

			$pattern =
				'%^# Match any youtube URL
				(?:https?://)?  # Optional scheme. Either http or https
				(?:www\.)?      # Optional www subdomain
				(?:             # Group host alternatives
				youtu\.be/    # Either youtu.be,
				| youtube\.com  # or youtube.com
				(?:           # Group path alternatives
				/embed/     # Either /embed/
				| /v/         # or /v/
				| .*v=        # or /watch\?v=
				)             # End path alternatives.
				)               # End host alternatives.
				([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
				($|&).*         # if additional parameters are also in query string after video id.
				$%x';

			$result = preg_match($pattern, $url, $matches);
			if (is_array($matches) && !empty($matches) && $result !== false) {
				return $matches[1];
			}

			return '';
		}

		/**
		 * Encrypts a Link
		 */
		public static function encryptLink(?string $link): string {
			if (self::isNullOrEmpty($link)) {
				return '';
			}

			return str_replace('&', '[amp;]', base64_encode($link));
		}

		/**
		 * Decrypts a Link
		 */
		public static function decryptLink(?string $link): string {
			if (self::isNullOrEmpty($link)) {
				return '';
			}

			return base64_decode(str_replace('[amp;]', '&', $link));
		}

		/**
		 * Get Status from the given code
		 */
		public static function getStatusFromCode(int $code): string {
			return match ($code) {
				Code::SUCCESS,
				HttpCode::OK,
				HttpCode::CREATED,
				HttpCode::ACCEPTED
				=> Status::SUCCESS,

				Code::ERROR,
				HttpCode::BADREQUEST,
				HttpCode::UNAUTHORIZED,
				HttpCode::FORBIDDEN,
				HttpCode::NOTFOUND,
				HttpCode::NOTALLOWED,
				HttpCode::UNPROCESSABLE,
				HttpCode::TOOMANYREQUESTS,
				HttpCode::INTERNALERROR,
				HttpCode::UNAVAILABLE
				=> Status::ERROR,

				Code::WARNING
				=> Status::WARNING,

				Code::INFO,
				Code::COMMON_INFO,
				HttpCode::CONTINUE,
				HttpCode::PROCESSING
				=> Status::INFO,

				default
				=> Status::NOT_AVAILABLE,
			};
		}

		/**
		 * Get content from the given file path
		 */
		public static function getContentFromFile(?string $filePath = null, ?array $replace = null): string {
			if (self::isNullOrEmpty($filePath) || !file_exists($filePath)) {
				return '';
			}

			//TODO: Throw an exception if the file does not exist
//			if (!file_exists($filePath)) {
//				throw new FileNotFoundException('filePath');
//			}

			$content = file_get_contents($filePath);
			if (!self::isNullOrEmpty($replace)) {
				$content = str_replace(
					array_keys($replace),
					array_values($replace),
					$content
				);
			}
			return $content;
		}


//		/**
//		 * Get JSON content from the given file path
//		 */
//		public static function GetJsonContentFromFileAsArray(
//			?string $filePath
//		): array {
//			if (self::isNullOrEmpty($filePath)) {
//				throw new NotEmptyParamException('filePath');
//			}
//			if (!file_exists($filePath)) {
//				throw new FileNotFoundException('filePath');
//			}
//			return json_decode(file_get_contents($filePath), true);
//		}
//
//
//		/**
//		 * Adds the root folder to a url, and converts it to a safe, user friendly URL
//		 */
//		//TODO: Is the same function in the Media??
//		public static function GenerateFullUrl(
//			string $page,
//			string $lang="",
//			array $safeParams=[],
//			array $optionalParams=[],
//			string $root="",
//			bool $safeUrl=true
//		) {
//			$args = "";
//			$finalSafeParams = [];
//
//			if ($lang != "") {
//				$finalSafeParams["lang"] = $lang;
//			}
//
//			foreach ($safeParams AS $k => $v) {
//				$finalSafeParams[$k] = $v;
//			}
//
//			foreach ($finalSafeParams AS $k => $v) {
//				if (!$safeUrl) {
//					$args .= $args === "" ? "?" : "&";
//				}
//				$args .= !$safeUrl ? $k . "=" . $v : "/" . $v;
//			}
//
//			foreach ($optionalParams as $k => $v){
//				if (is_array($v)) {
//					foreach ($v AS $v1) {
//						if ($v1 !== "") {
//							$args .= (strpos($args, "?") === false ? "?" : "&") . $k . "%5B%5D=" . $v1 ;
//						}
//					}
//				}
//				else {
//					if ($v !== "") {
//						$args .= (strpos($args, "?") === false ? "?" : "&") . $k . "=" . $v ;
//					}
//				}
//			}
//
//			if (!self::isNullOrEmpty($root) && !self::StringEndsWith($root, "/")) {
//				$root .= "/";
//			}
//			$url = $root . $page . $args;
//
//			$url = self::RemoveMultipleSlashesInUrl($url);
//
//			return $url ;
//		}
//
//

//
//
//		/**
//		 * Adds a version parameter to the given path
//		 */
//		public static function AddVersionParameterToPath(
//			string $path,
//			string $websiteRoot,
//			string $version=""
//		) {
//			return self::GenerateFullUrl($path, "", [], [
//				"v" => $version
//			], $websiteRoot);
//		}
//
//
//		/**
//		 * Get all files in a path
//		 */
//		public static function GetAllFiles(
//			string $path,
//			bool $recursive=false
//		): array {
//			$filesArr = [];
//
//			if (is_dir($path)) {
//				$files = scandir($path);
//
//				foreach ($files AS $file) {
//					$_file = $path . "/" . $file;
//
//					if (!is_dir($_file)) {
//						$filesArr[] = $_file;
//					}
//					else if ($recursive && $file !== "." && $file !== "..") {
//						$filesArr = array_merge($filesArr, self::GetAllFiles($_file, $recursive));
//					}
//				}
//			}
//			return $filesArr;
//		}
//
//		/**
//		 * Get all folders in a path
//		 */
//		public static function GetAllFolders(
//			string $path,
//			bool $recursive=false
//		): array {
//			$foldersArr = [];
//
//			if (is_dir($path)) {
//				$folders = scandir($path);
//
//				foreach ($folders AS $folder) {
//					$_folder = $path . "/" . $folder;
//
//					if (is_dir($_folder) && $folder !== "." && $folder !== "..") {
//						$foldersArr[] = $_folder;
//						if ($recursive) {
//							$foldersArr = array_merge($foldersArr, self::GetAllFolders($_folder, true));
//						}
//					}
//				}
//			}
//			return $foldersArr;
//		}
//
//		/**
//		 * Delete all Files and Folders in a given Path. Also Delete the Main Folder is $deletePath is set to true
//		 */
//		public static function DeleteFoldersAndFiles(
//			string $path,
//			bool $deletePath=false
//		): void {
//			$filesArr = self::GetAllFiles($path, true);
//			$foldersArr = array_reverse(self::GetAllFolders($path, true));
//
//			foreach ($filesArr AS $file) {
//				self::DeleteFileOrFolder($file);
//			}
//			foreach ($foldersArr AS $folder) {
//				self::DeleteFileOrFolder($folder);
//			}
//			if ($deletePath) {
//				self::DeleteFileOrFolder($path);
//			}
//		}
//
//		/**
//		 * Converts a multidimentional array to a single dimentional array
//		 */
//		public static function ConvertMultidimentionArrayToSingleDimention(
//			array $arrayToConvert,
//			string $preKey=""
//		): array {
//			$returnArray = [];
//
//			foreach ($arrayToConvert AS $k => $v) {
//				if (is_array($v)) {
//					$returnArray = array_merge($returnArray,
//						self::ConvertMultidimentionArrayToSingleDimention($v, $preKey . $k . ".")
//					);
//				}
//				else {
//					$returnArray[$preKey . $k] = $v;
//				}
//			}
//			return $returnArray;
//		}
//
//
//		/**
//		 * Add scheme to the given string if not exists
//		 */
//		public static function AddSchemeIfMissing(
//			string $string,
//			string $scheme
//		): string {
//			if (self::isNullOrEmpty($string)) {
//				return "";
//			}
//			if (self::isNullOrEmpty($scheme)) {
//				return $string;
//			}
//			if (self::IsValidUrl($string)) {
//				return $string;
//			}
//
//			if (!self::StringEndsWith($scheme, "://")) {
//				$scheme .= "://";
//			}
//			return $scheme . $string;
//		}
//
//
//		/**
//		 * Replace scheme of the given string with the given scheme
//		 */
//		public static function ReplaceScheme(
//			string $string,
//			string $scheme
//		): string {
//			if (self::isNullOrEmpty($string)) {
//				return "";
//			}
//			if (self::isNullOrEmpty($scheme)) {
//				return $string;
//			}
//
//			if (self::IsValidUrl($string)) {
//				$string = str_replace(["http://", "https://"], "", $string);
//			}
//
//			if (!self::StringEndsWith($scheme, "://")) {
//				$scheme .= "://";
//			}
//			return $scheme . $string;
//		}
//
//
//		/**
//		 * Check if the given string is a valid link
//		 */
//		public static function IsValidUrl(string $string): bool {
//			return self::StringBeginsWith($string, ["http://", "https://"]);
//		}
//
//		public static function MissingParams(array $params, array $required): array {
//			$paramKeys = array_keys($params);
//			$missing = array_values(array_diff($required, $paramKeys));
//			$found = array_values(array_intersect($required, $paramKeys));
//
//			return [
//				'missing' => $missing,
//				'found' => $found
//			];
//		}
//
//		public static function MissingParamsThrows(array $params, array $required): void {
//			[
//				'missing' => $missingParams
//			] = Helper::MissingParams($params, $required);
//
//			if (!empty($missingParams)) {
//				throw new MissingParamsException($missingParams);
//			}
//		}
//
//		public static function MissingNotEmptyParams(array $params, array $required): array {
//			[
//				'missing' => $missing,
//				'found' => $found,
//			] = self::MissingParams($params, $required);
//
//			foreach ($found as $index => $foundKey) {
//				if (self::isNullOrEmpty($params[$foundKey])) {
//					$missing[] = $foundKey;
//					unset($found[$index]);
//				}
//			}
//
//			return [
//				'missing' => array_values($missing),
//				'found' => array_values($found)
//			];
//		}
//
//		public static function MissingNotEmptyParamsThrows(array $params, array $required): void {
//			[
//				'missing' => $missingParams
//			] = Helper::MissingNotEmptyParams($params, $required);
//
//			if (!empty($missingParams)) {
//				throw new MissingParamsException($missingParams);
//			}
//		}

	}
