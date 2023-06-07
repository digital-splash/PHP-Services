<?php
	namespace DigitalSplash\Models;

	class Tenant {

	private static $TENANT_NAME;
	private static $TENANT_DOMAIN;
	private static $TENANT_PRIMARY_COLOR;
	private static $TENANT_YEAR;
	private static $TENANT_LOGO;

	public static function setName(string $name): void {
		self::$TENANT_NAME = $name;
	}

	public static function getName(): string {
		return self::$TENANT_NAME;
	}

	public static function setDomain(string $domain): void {
		self::$TENANT_DOMAIN = $domain;
	}

	public static function getDomain(): string {
		return self::$TENANT_DOMAIN;
	}

	public static function setPrimaryColor(string $color): void {
		self::$TENANT_PRIMARY_COLOR = $color;
	}

	public static function getPrimaryColor(): string {
		return self::$TENANT_PRIMARY_COLOR;
	}

	public static function setYear(string $year): void {
		self::$TENANT_YEAR = $year;
	}

	public static function getYear(): string {
		return self::$TENANT_YEAR;
	}

	public static function setLogo(string $logo): void {
		self::$TENANT_LOGO = $logo;
	}

	public static function getLogo(): string {
		return self::$TENANT_LOGO;
	}

}