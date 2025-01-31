<?php

	namespace DigitalSplash\Tests\Core;

	use DigitalSplash\Core\Env;
	use DigitalSplash\Exceptions\Configuration\ConfigurationNotFoundException;
	use DigitalSplash\Exceptions\Configuration\InvalidConfigurationException;
	use PHPUnit\Framework\TestCase;

	class EnvTest extends TestCase {
		public function testGetConfigFromFileFileNotFoundThrows(): void {
			$this->expectException(ConfigurationNotFoundException::class);
			Env::setEnvFileName('test-env.json');
			Env::init(true);
		}

		public function testGetConfigFromFileFileEmptyThrows(): void {
			$this->expectException(InvalidConfigurationException::class);
			Env::setEnvFileName('empty-env.json');
			Env::init(true);
		}

		public function testInitUrlNoParamsEmptySuccess(): void {
			Env::init();

			$this->assertEmpty(Env::$urlNoParams);
		}

		public function testInitUrlNoParamsNotEmptySuccess(): void {
			$_SERVER['REQUEST_SCHEME'] = 'https';
			$_SERVER['SERVER_NAME'] = 'test.com';
			$_SERVER['REQUEST_URI'] = '/test';

			Env::setEnvFileName('env.json');
			Env::init(true);

			$this->assertEquals('https://test.com/test/', Env::$urlNoParams);
		}

		public function testGetByKeyEmpty(): void {
			$value = Env::getByKey('test');
			$this->assertEmpty($value);
		}

		public function testGetByKey(): void {
			$env = Env::getByKey('env');
			$this->assertNotEmpty($env);
		}
	}