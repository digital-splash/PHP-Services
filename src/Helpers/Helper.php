<?php
	namespace DigitalSplash\Helpers;

	use DigitalSplash\Exceptions\FileNotFoundException;
	use DigitalSplash\Exceptions\InvalidParamException;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Models\Code;
	use DigitalSplash\Models\HttpCode;
	use DigitalSplash\Language\Models\Lang;
	use DigitalSplash\Models\Status;

	class Helper {


		/**
		 * Returns a Clean String
		 */
		public static function CleanString(
			string $str
		): string {
			$str = trim($str);
			$str = stripslashes($str);

			//TODO: mysqli escape ?

			return $str;
		}


		/**
		 * Converts HTML tags in a string to be visible, and vice versa
		 */
		public static function CleanHtmlText(
			string $data,
			bool $convertSpecialChars=true
		): string {
			$data = self::CleanString($data);

			if ($convertSpecialChars) {
				$data = htmlspecialchars($data, ENT_QUOTES);
			}

			return $data;
		}


		/**
		 * Converts a value to a boolean
		 */
		public static function ConvertToBool(
			$val
		) : bool {
			switch (gettype($val)) {
				case "boolean":
					return $val;
				case "string":
					$val = trim($val);
					if (
						Helper::IsNullOrEmpty($val)
						||
						$val === "false"
					) {
						return false;
					}
					return true;

				case "integer":
				case "double":
					$val = Helper::ConvertToDec($val);
					return $val > 0;
					break;
			}

			return false; //TODO: Maybe use boolval()
		}


		/**
		 * Converts a value to an integer
		 */
		public static function ConvertToInt(
			$val
		): int {
			if ((isset($val)) && (trim($val) !== "") && is_numeric($val)) {
				if ($val < 0) {
					return intval($val);
				}
				return round($val) ;
			}
			return 0;
		}


		/**
		 * Converts a value to a decimal
		 */
		public static function ConvertToDec(
			$val,
			int $decimalPlaces=2
		): float {
			if ((isset($val)) && (trim($val) !== "")) {
				$val = round(floatval($val), $decimalPlaces);
				if (is_numeric($val) && is_nan($val)) {
					return 0;
				}
				return $val;
			}
			return 0;
		}


		/**
		 * Converts a value to a decimal and returns as a String
		 */
		public static function ConvertToDecAsString(
			$val,
			int $decimalPlaces=0
		): string {
			return number_format(self::ConvertToDec($val, $decimalPlaces), $decimalPlaces);
		}

		public static function IsNullOrEmpty(
			$value
		): bool {
			return empty($value);
		}


		/**
		 * Encrypt a String
		 */
		public static function EncryptString(
			string $string
		): string {
			return hash("sha512", trim($string));
		}


		/**
		 * Generate a Random String
		 */
		public static function GenerateRandomKey(
			int $length=8,
			bool $hasInt=true,
			bool $hasString=false,
			bool $hasSymbols=false,
			string $lang=Lang::EN
		): string {
			$key = "";
			$possible = "";

			if ($hasInt) {
				if ($lang == Lang::EN || $lang == Lang::ALL) {
					$possible .= "0123456789";
				}
				if ($lang == Lang::AR || $lang == Lang::ALL) {
					$possible .= "٠١٢٣٤٥٦٧٨٩";
				}
			}

			if ($hasString) {
				if ($lang == Lang::EN || $lang == Lang::ALL) {
					$possible .= "abcdefghijklmnopqrstuvwxyz";
					$possible .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				}
				if ($lang == Lang::AR || $lang == Lang::ALL) {
					// $possible .= "ابتثجحخدذرزسشصضطظعغفقكلمنهوي";
					$possible .= "ضصثقفغعهخحجدشسيبلاتنمكطئءؤرلاىةوزظ";
				}
			}

			if ($hasSymbols) {
				$possible .= "!@#$%^&*()_-+=?\/|`~.,<>";
			}

			if ($possible !== "") {
				/* Add random characters to $key until $length is reached */
				for ($i = 0; $i < $length; $i++) {
					$minRandNb	= 0;
					$maxRandNb	= strlen($possible)-1;
					$rand		= mt_rand($minRandNb, $maxRandNb);

					/* Pick a random character from the possible ones */
					$char = substr($possible, $rand, 1);

					$key .= $char;
				}
			}

			return $key;
		}


		/**
		 * Removes all the slashes from a string
		 */
		public static function RemoveSlashes(
			string $str
		): string {
			return stripslashes(trim(implode("", explode("\\", $str))));
		}


		/**
		 * Removes all the spaces from a string
		 */
		public static function RemoveSpaces(
			string $str
		): string {
			return str_replace(" ", "", trim($str));
		}


		/**
		 * Limit a text to a fixed number of characters
		 */
		public static function TruncateStr(
			string $text,
			int $nbOfChar,
			string $extension="...",
			string $lang=Lang::EN
		): string {
			if ($lang == Lang::AR) {
				$nbOfChar = $nbOfChar * 1.8;
			}

			$text = self::CleanString($text);

			if (strlen($text) > $nbOfChar) {
				$text = substr($text, 0, $nbOfChar) . $extension;
			}

			return $text;
		}


		/**
		 * Search if is a string begins with a special characters combination
		 */
		public static function StringBeginsWith(
			string $string,
			$search
		): bool {
			if (is_array($search)) {
				foreach ($search AS $s) {
					if ((strncmp($string, $s, strlen($s)) == 0)) {
						return true;
					}
				}
				return false;
			}
			else {
				return (strncmp($string, $search, strlen($search)) == 0);
			}
		}


		/**
		 * Search if is a string end with a special characters combination
		 */
		public static function StringEndsWith(
			string $string,
			$search
		): bool {
			if (is_array($search)) {
				foreach ($search AS $s) {
					if (substr($string, (strlen($string) - strlen($s))) == $s) {
						return true;
					}
				}
				return false;
			}
			else {
				return substr($string, (strlen($string) - strlen($search))) == $search ? true : false;
			}
		}


		/**
		 * Search if is a string contains a value
		 */
		public static function StringHasChar(
			string $string,
			$search
		): bool {
			if (is_array($search)) {
				foreach ($search as $searchKey) {
					if (strpos($string, $searchKey) !== false) {
						return true;
					}
				}
				return false;
			}
			if (is_string($search)) {
				return strpos($string, $search) !== false;
			}
			throw new InvalidParamException("search");
		}


		/**
		 * Check if a given substring exists in a string
		 */
		public static function IsInString(
			string $string,
			string $search
		): bool {
			return strpos($string, $search) !== false ? true : false;
		}


		/**
		 * Search for the allowed tags in the content and display them
		 */
		public static function StripHtml(
			string $content,
			$allow=""
		): string {
			return strip_tags($content, $allow);
		}


		/**
		 * Replace all values in a text
		 */
		public static function TextReplace(
			string $text,
			array $params=[]
		): string {
			return str_replace(
				array_keys($params),
				array_values($params),
				$text
			);
		}


		/**
		 * Separate Camel Case String
		 */
		public static function SplitCamelcaseString(
			string $str,
			string $split=" "
		): string {
			$pieces = preg_split("/(?=[A-Z])/", $str);
			return trim(implode($split, $pieces));
		}


		/**
		 * Get the string value safely
		 */
		public static function GetStringSafe(
			?string $str
		): string {
			if (self::IsNullOrEmpty($str)) {
				return "";
			}
			return $str;
		}


		/**
		 * Generates a Class Name from the Given String
		 */
		public static function GenerateClassNameFromString(
			string $str
		): string {
			return str_replace(
				" ",
				"",
				ucwords(
					str_replace(
						"-",
						" ",
						$str
					)
				)
			);
		}


		/**
		 * Converts the given string into a safe one | Supports English & Arabic
		 */
		public static function SafeName(
			string $str,
			string $trimChar="-"
		): string {
			$friendlyURL = htmlentities($str, ENT_COMPAT, "UTF-8", false);
			$friendlyURL = preg_replace('/&([a-z]{1,2})(?:acute|lig|grave|ring|tilde|uml|cedil|caron);/i','\1',$friendlyURL);
			$friendlyURL = html_entity_decode($friendlyURL,ENT_COMPAT, "UTF-8");
			$friendlyURL = preg_replace ( "/[^أ-يa-zA-Z0-9٠-٩_.-]/u", $trimChar, $friendlyURL );
			$friendlyURL = preg_replace('/-+/', $trimChar, $friendlyURL);
			$friendlyURL = trim($friendlyURL, $trimChar);

			$isArabic = self::HasArabicChar($str);
			if (!$isArabic) {
				$friendlyURL = strtolower($friendlyURL);
			}

			return $friendlyURL;
		}


		/**
		 * Checks if the given str contains any arabic characters
		 */
		public static function HasArabicChar(
			string $str
		): bool {
			if(mb_detect_encoding($str) !== 'UTF-8') {
				$str = mb_convert_encoding($str, mb_detect_encoding($str), "UTF-8");
			}

			/*
			$str = str_split($str); <- this function is not mb safe, it splits by bytes, not characters. we cannot use it
			$str = preg_split('//u',$str); <- this function would probably work fine but there was a bug reported in some php version so it pslits by bytes and not chars as well
			*/

			preg_match_all("/.|\n/u", $str, $matches);
			$chars = $matches[0];
			$arabic_count = 0;
			$latin_count = 0;
			$total_count = 0;

			foreach ($chars AS $char) {
				/* BEGIN: I just copied this function from the php.net comments, but it should work fine! */
				$k = mb_convert_encoding($char, 'UCS-2LE', 'UTF-8');
				$k1 = ord(substr($k, 0, 1));
				$k2 = ord(substr($k, 1, 1));

				$pos = $k2 * 256 + $k1;
				/* END: I just copied this function from the php.net comments, but it should work fine! */

				if ($pos >= 1536 && $pos <= 1791) {
					$arabic_count++;
				}
				else if ($pos > 123 && $pos < 123) {
					$latin_count++;
				}
				$total_count++;
			}

			return $arabic_count > 0;
		}


		/**
		 * Converts the given String into an Array
		 */
		public static function ExplodeStrToArr(
			string $separator='',
			?string $string,
			int $chunkLength=0
		): array {
			if (self::IsNullOrEmpty($string)) {
				return [];
			}

			if (!self::IsNullOrEmpty($separator)) {
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
		public static function ImplodeArrToStr(
			string $separator=' ',
			?array $array
		): string {
			if (self::IsNullOrEmpty($array)) {
				return '';
			}
			return implode($separator, self::UnsetArrayEmptyValues($array));
		}


		/**
		 * Get the value of the given key in a given array
		 */
		public static function GetValueFromArrByKey(
			?array $arr,
			string $key=""
		): string {
			if (self::IsNullOrEmpty($arr) || !isset($arr[$key])) {
				return "";
			}
			return $arr[$key];
		}


		/**
		 * Unset Empty Values from the given object/array
		 */
		public static function UnsetArrayEmptyValues(
			?array $array
		): array {
			if (self::IsNullOrEmpty($array)) {
				return [];
			}

			return array_values(
				array_filter(
					$array,
					function($value) {
						if (!Helper::IsNullOrEmpty($value)) {
							return $value;
						}
					}
				)
			);
		}


		/**
		 * Generate Key Value String from Array
		 */
		public static function GererateKeyValueStringFromArray(
			?array $params,
			string $keyPrefix="",
			string $keyValueJoin="=",
			string $valueHolder="\"",
			string $elemsJoin=" "
		): string {
			if (self::IsNullOrEmpty($params)) {
				return "";
			}

			$str = "";
			foreach ($params AS $k => $v) {
				$k = $keyPrefix . $k;
				$str .= ($str != "" ? $elemsJoin : "") . $k . $keyValueJoin . $valueHolder . $v . $valueHolder;
			}
			return $str;
		}


		/**
		 * Checks if the given directory is available in the domain folders
		 */
		public static function DirExists(
			?string $dirName,
			string $path="./",
			bool $checkSubFolders=false
		): bool {
			if (self::IsNullOrEmpty($dirName)) {
				return false;
			}

			if (is_dir($path . $dirName)) {
				return true;
			}

			if ($checkSubFolders) {
				$tree = glob($path . "*", GLOB_ONLYDIR);
				if ($tree && count($tree) > 0) {
					foreach ($tree AS $dir) {
						if (self::DirExists($dirName, $dir . "/")) {
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
		public static function CreateFolder(
			string $dir,
			string $permission="0777"
		): bool {
			// var_dump($dir);
			if (!is_dir($dir)) {
				mkdir($dir, $permission, true);
				return true;
			}
			return false;
		}


		/**
		 * Create all the unfound folders in a given path
		 */
		public static function CreateFolderRecursive(
			string $dir,
			string $permission="0777"
		): bool {
			if (self::DirExists($dir)) {
				return true;
			}

			$foldersToCreate = [
				$dir
			];

			$i = 1;
			while (true === true) {
				$newDir = dirname($dir, $i);
				if (self::DirExists($newDir, "")) {
					break;
				}
				$foldersToCreate[] = $newDir;
				$i++;
			}
			krsort($foldersToCreate);
			foreach ($foldersToCreate AS $folderToCreate) {
				self::CreateFolder($folderToCreate, $permission);
			}

			return true;
		}


		/**
		 * Delete the given file/folder
		 */
		public static function DeleteFileOrFolder(
			string $dir
		): bool {
			if (file_exists($dir)) {
				if (is_dir($dir)) {
					rmdir($dir);
					return true;
				}

				if (!is_dir($dir)) {
					unlink($dir);
					return true;
				}
			}
			return false;
		}


		/**
		 * Retreive Youtube embed id from the video full link
		 */
		public static function GetYoutubeId(
			?string $url
		): string {
			if (self::IsNullOrEmpty($url)) {
				return "";
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
			if ($result !== false) {
				return $matches[1];
			}

			return "";
		}


		/**
		 * Encrypt a Link
		 */
		public static function EncryptLink(
			?string $link
		): string {
			if (self::IsNullOrEmpty($link)) {
				return "";
			}
			return str_replace("&", "[amp;]", base64_encode($link));
		}


		/**
		 * Dencrypt a Link
		 */
		public static function DecryptLink(
			?string $link
		): string {
			if (self::IsNullOrEmpty($link)) {
				return "";
			}
			return base64_decode(str_replace("[amp;]", "&", $link));
		}


		/**
		 * Get Status Class from the given code
		 */
		public static function GetStatusClassFromCode(
			int $code
		): string {
			switch ($code) {
				case Code::SUCCESS:
				case HttpCode::OK:
				case HttpCode::CREATED:
				case HttpCode::ACCEPTED:
					return Status::SUCCESS;

				case Code::ERROR:
				case HttpCode::BADREQUEST:
				case HttpCode::UNAUTHORIZED:
				case HttpCode::FORBIDDEN:
				case HttpCode::NOTFOUND:
				case HttpCode::NOTALLOWED:
				case HttpCode::INTERNALERROR:
				case HttpCode::UNAVAILABLE:
					return Status::ERROR;

				case Code::WARNING:
					return Status::WARNING;

				case Code::INFO:
				case Code::COMMON_INFO:
				case HttpCode::CONTINUE:
				case HttpCode::PROCESSING:
					return Status::INFO;

				default:
					return Status::INFO;
			}
		}


		/**
		 * Get content from the given file path
		 */
		public static function GetContentFromFile(
			?string $filePath=null,
			?array $replace=null
		): string {
			if (self::IsNullOrEmpty($filePath)) {
				throw new NotEmptyParamException('filePath');
			}
			if (!file_exists($filePath)) {
				throw new FileNotFoundException('filePath');
			}

			$content = file_get_contents($filePath);
			if (!Helper::IsNullOrEmpty($replace)) {
				$content = str_replace(
					array_keys($replace),
					array_values($replace),
					$content
				);
			}
			return $content;
		}


		/**
		 * Get JSON content from the given file path
		 */
		public static function GetJsonContentFromFileAsArray(
			?string $filePath
		): array {
			if (self::IsNullOrEmpty($filePath)) {
				throw new NotEmptyParamException('filePath');
			}
			if (!file_exists($filePath)) {
				throw new FileNotFoundException('filePath');
			}
			return json_decode(file_get_contents($filePath), true);
		}


		/**
		 * Adds the root folder to a url, and converts it to a safe, user friendly URL
		 */
		public static function GenerateFullUrl(
			string $page,
			string $lang="",
			array $safeParams=[],
			array $optionalParams=[],
			string $root="",
			bool $safeUrl=true
		) {
			$args = "";
			$finalSafeParams = [];

			if ($lang != "") {
				$finalSafeParams["lang"] = $lang;
			}

			foreach ($safeParams AS $k => $v) {
				$finalSafeParams[$k] = $v;
			}

			foreach ($finalSafeParams AS $k => $v) {
				if (!$safeUrl) {
					$args .= $args === "" ? "?" : "&";
				}
				$args .= !$safeUrl ? $k . "=" . $v : "/" . $v;
			}

			foreach ($optionalParams as $k => $v){
				if (is_array($v)) {
					foreach ($v AS $v1) {
						if ($v1 !== "") {
							$args .= (strpos($args, "?") === false ? "?" : "&") . $k . "%5B%5D=" . $v1 ;
						}
					}
				}
				else {
					if ($v !== "") {
						$args .= (strpos($args, "?") === false ? "?" : "&") . $k . "=" . $v ;
					}
				}
			}

			if (!self::IsNullOrEmpty($root) && !self::StringEndsWith($root, "/")) {
				$root .= "/";
			}
			$url = $root . $page . $args;

			$urlScheme = "";
			if (self::StringBeginsWith($url, "http://")) {
				$urlScheme = "http://";
			}
			if (self::StringBeginsWith($url, "https://")) {
				$urlScheme = "https://";
			}

			if (!self::IsNullOrEmpty($urlScheme)) {
				$url = str_replace($urlScheme, "", $url);
			}

			while (strpos($url, "//") !== false) {
				$url = str_replace("//", "/", $url) ;
			}
			$url = $urlScheme . $url;

			return $url ;
		}


		/**
		 * Adds a version parameter to the given path
		 */
		public static function AddVersionParameterToPath(
			string $path,
			string $websiteRoot,
			string $version=""
		) {
			return self::GenerateFullUrl($path, "", [], [
				"v" => $version
			], $websiteRoot);
		}


		/**
		 * Get all files in a path
		 */
		public static function GetAllFiles(
			string $path,
			bool $recursive=false
		): array {
			$filesArr = [];

			if (is_dir($path)) {
				$files = scandir($path);

				foreach ($files AS $file) {
					if (!is_dir($path . "/" . $file)) {
						$filesArr[] = $path . "/" . $file;
					}
					else {
						if ($recursive && $file !== "." && $file !== "..") {
							$filesArr = array_merge($filesArr, self::GetAllFiles($path . "/" . $file, $recursive));
						}
					}
				}
			}
			return $filesArr;
		}


		/**
		 * Converts a multidimentional array to a single dimentional array
		 */
		public static function ConvertMultidimentionArrayToSingleDimention(
			array $arrayToConvert,
			string $preKey=""
		): array {
			$returnArray = [];

			foreach ($arrayToConvert AS $k => $v) {
				if (is_array($v)) {
					$returnArray = array_merge($returnArray,
						self::ConvertMultidimentionArrayToSingleDimention($v, $preKey . $k . ".")
					);
				}
				else {
					$returnArray[$preKey . $k] = $v;
				}
			}
			return $returnArray;
		}


		/**
		 * Add scheme to the given string if not exists
		 */
		public static function AddSchemeIfMissing(
			string $string,
			string $scheme
		): string {
			if (self::IsNullOrEmpty($string)) {
				return "";
			}
			if (self::IsNullOrEmpty($scheme)) {
				return $string;
			}
			if (self::IsValidUrl($string)) {
				return $string;
			}

			if (!self::StringEndsWith($scheme, "://")) {
				$scheme .= "://";
			}
			return $scheme . $string;
		}


		/**
		 * Replace scheme of the given string with the given scheme
		 */
		public static function ReplaceScheme(
			string $string,
			string $scheme
		): string {
			if (self::IsNullOrEmpty($string)) {
				return "";
			}
			if (self::IsNullOrEmpty($scheme)) {
				return $string;
			}

			if (self::IsValidUrl($string)) {
				$string = str_replace(["http://", "https://"], "", $string);
			}

			if (!self::StringEndsWith($scheme, "://")) {
				$scheme .= "://";
			}
			return $scheme . $string;
		}


		/**
		 * Check if the given string is a valid link
		 */
		public static function IsValidUrl(string $string): bool {
			return self::StringBeginsWith($string, ["http://", "https://"]);
		}

	}
