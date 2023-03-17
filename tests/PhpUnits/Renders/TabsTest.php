<?php
	namespace DigitalSplash\Tests\Renders;

	//To Run: .\vendor/bin/phpunit .\tests\PhpUnits\Renders\TabsTest.php

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Renders\Tabs;

	class TabsTest extends TestCase {

		public function testEmptyTabSuccess() {
			$tabs = new Tabs("default_tabs", "tabs1");
			$this->assertEmpty($tabs->Render());
		}
	}
