<?php
    namespace DigitalSplash\Database\Helpers;

	use DigitalSplash\Helpers\Helper;

    class DBHelper {

        public static function calculateHash(
            string $engine,
			string $host,
			string $username,
			string $password,
			string $database,
			?string $port = null
		): string {
			$tempPort = self::GetPortFromHost($host);
			if (!Helper::StringNullOrEmpty($tempPort)) {
				$port = $tempPort;
			}

			return Helper::EncryptString(Helper::ImplodeArrToStr(';', [
                $engine,
				$host,
				$port,
				$username,
				$password,
				$database
			]));
		}

        public static function GetPortFromHost(
			string &$host
		): string {
			if (Helper::StringHasChar($host, ':')) {
				[
					$host,
					$port
				] = Helper::ExplodeStrToArr(':', $host);

				if (!Helper::StringNullOrEmpty($port)) {
					return $port;
				}
			}
			return '';
		}

        public static function isHashValid(
            string $hash,
            string $engine,
			string $host,
			string $username,
			string $password,
			string $database,
			?string $port = null
		): bool {
			return $hash === self::calculateHash($engine, $host, $username, $password, $database, $port);
		}

    }