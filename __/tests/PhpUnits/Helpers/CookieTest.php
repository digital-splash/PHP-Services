<?php
	namespace DigitalSplash\Tests\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Exceptions\InvalidCookieException;
	use DigitalSplash\Helpers\Cookie;

	final class CookieTest extends TestCase {

		public function testSettersAndGettersSuccess() {
			$now = time();

			Cookie::SetExpireInUnix(time());
			$this->assertEquals($now, Cookie::GetExpire());

			Cookie::SetExpireInDays(10);
			$this->assertEquals($now + (60 * 60 * 24 * 10), Cookie::GetExpire());

			Cookie::SetExpireInUnix(0);
			$this->assertEquals(time() + (60 * 60 * 24 * 365), Cookie::GetExpire());

			$this->assertEquals("rm_", Cookie::GetPrefix());

			Cookie::SetPrefix("testcookie_");
			$this->assertEquals("testcookie_", Cookie::GetPrefix());

			$this->assertEquals("/", Cookie::GetPath());

			Cookie::SetPath("/test/path/");
			$this->assertEquals("/test/path/", Cookie::GetPath());

			Cookie::SetDomain("testcookie.com");
			$this->assertEquals("testcookie.com", Cookie::GetDomain());

			$this->assertFalse(Cookie::GetSecure());

			Cookie::SetSecure(true);
			$this->assertTrue(Cookie::GetSecure());

			$this->assertFalse(Cookie::GetHttpOnly());

			Cookie::SetHttpOnly(true);
			$this->assertTrue(Cookie::GetHttpOnly());
		}

		public function testGetCookieThrowError() {
			$this->expectException(InvalidCookieException::class);
			Cookie::Get("invalid");
		}
	}
