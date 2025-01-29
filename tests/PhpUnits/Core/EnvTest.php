<?php

	namespace DigitalSplash\Tests\Core;

	use DigitalSplash\Core\Env;
	use DigitalSplash\Exceptions\Configuration\ConfigurationNotFoundException;
	use DigitalSplash\Exceptions\Configuration\InvalidConfigurationException;
	use PHPUnit\Framework\TestCase;

	class EnvTest extends TestCase {
		public function testGetConfigFromFileFileNotFoundThrows(): void {
			$this->expectException(ConfigurationNotFoundException::class);
			Env::init('test-env.json');
		}

		public function testGetConfigFromFileFileEmptyThrows(): void {
			$this->expectException(InvalidConfigurationException::class);
			Env::init('empty-env.json');
		}

		public function testInitUrlNoParamsEmptySuccess(): void {
			Env::init();

			$this->assertEmpty(Env::$urlNoParams);
		}

		public function testInitUrlNoParamsNotEmptySuccess(): void {
			$_SERVER['REQUEST_SCHEME'] = 'https';
			$_SERVER['SERVER_NAME'] = 'test.com';
			$_SERVER['REQUEST_URI'] = '/test';

			Env::init();

			$this->assertEquals('https://test.com/test/', Env::$urlNoParams);
		}

		public function testGetByKeyEmpty(): void {
			$value = Env::getByKey('test');
			$this->assertEmpty($value);
		}

		public function testGetByKey(): void {
			Env::init();

			$env = Env::getByKey('env');
			$this->assertNotEmpty($env);
		}
	}