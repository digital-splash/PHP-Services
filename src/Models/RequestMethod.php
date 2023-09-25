<?php
	namespace DigitalSplash\Models;

	class RequestMethod {
		public const GET = 'GET';
		public const POST = 'POST';
		public const PUT = 'PUT';
		public const DELETE	= 'DELETE';
		public const PATCH = 'PATCH';

		public static function get(): string {
			return $_SERVER['REQUEST_METHOD'];
		}

		public static function isGet(): bool {
			return self::get() === self::GET;
		}

		public static function isPost(): bool {
			return self::get() === self::POST;
		}

		public static function isPut(): bool {
			return self::get() === self::PUT;
		}

		public static function isPatch(): bool {
			return self::get() === self::PATCH;
		}

		public static function isDelete(): bool {
			return self::get() === self::DELETE;
		}
	}
