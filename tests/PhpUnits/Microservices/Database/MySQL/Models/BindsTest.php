<?php
	namespace DigitalSplash\Tests\Database\MySQL\Models;

	use DigitalSplash\Database\MySQL\Models\Binds;
	use DigitalSplash\Exceptions\NotEmptyParamException;
	use DigitalSplash\Language\Helpers\Translate;
use PDO;
use PHPUnit\Framework\TestCase;

    class BindsTest extends TestCase {
                
            public function testGetBinds(): void {
                $binds = new Binds();
                $this->assertEqualsCanonicalizing([], $binds->getBinds());
            }


            
    }