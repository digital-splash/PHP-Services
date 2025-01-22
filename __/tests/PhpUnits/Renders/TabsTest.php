<?php
	namespace DigitalSplash\Tests\Renders;


	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Renders\Tabs;

	class TabsTest extends TestCase {

		public function testEmptyTabSuccess() {
			$tabs = new Tabs("default_tabs", "tabs1");
			$this->assertEmpty($tabs->Render());
		}
	}
