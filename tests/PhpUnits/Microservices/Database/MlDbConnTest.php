<?php
	// namespace NutriPro\Tests\Database;

	// use NutriPro\Database\MlDbConn;
	// use NutriPro\Tests\Utils\DbTestUtils;
	// use PHPUnit\Framework\TestCase;

	// class MlDbConnTest extends TestCase {

	// 	public static function setUpBeforeClass(): void {
	// 		MlDbConn::executeRawQueryStatic("CREATE TABLE IF NOT EXISTS `test3` (
	// 			`Id` int(11) NOT NULL AUTO_INCREMENT,
	// 			`Title` varchar(255) NOT NULL,
	// 			`Content` text NOT NULL,
	// 			`Active` tinyint(1) NOT NULL DEFAULT '1',
	// 			`Deleted` tinyint(1) NOT NULL DEFAULT '0',
	// 			`DeletedDate` datetime DEFAULT NULL,
	// 			`CreatedOn` datetime DEFAULT NULL,
	// 			`LastUpdated` datetime DEFAULT NULL,
	// 			PRIMARY KEY (`Id`)
	// 		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	// 		MlDbConn::executeRawQueryStatic("CREATE TABLE IF NOT EXISTS `test3_langs` (
	// 			`Id` int(11) NOT NULL AUTO_INCREMENT,
	// 			`MainId` int(11) NOT NULL,
	// 			`Lang` varchar(5) NOT NULL,
	// 			`Title` varchar(255) NOT NULL,
	// 			`Content` text NOT NULL,
	// 			PRIMARY KEY (`Id`)
	// 		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	// 		MlDbConn::executeRawQueryStatic("CREATE TABLE IF NOT EXISTS `test4` (
	// 			`Id` int(11) NOT NULL AUTO_INCREMENT,
	// 			`Title` varchar(255) NOT NULL,
	// 			`Active` tinyint(1) NOT NULL DEFAULT '1',
	// 			`Deleted` tinyint(1) NOT NULL DEFAULT '0',
	// 			`DeletedDate` datetime DEFAULT NULL,
	// 			`CreatedOn` datetime DEFAULT NULL,
	// 			`LastUpdated` datetime DEFAULT NULL,
	// 			PRIMARY KEY (`Id`)
	// 		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	// 		MlDbConn::executeRawQueryStatic("CREATE TABLE IF NOT EXISTS `test4_langs` (
	// 			`Id` int(11) NOT NULL AUTO_INCREMENT,
	// 			`MainId` int(11) NOT NULL,
	// 			`Lang` varchar(5) NOT NULL,
	// 			`Title` varchar(255) NOT NULL,
	// 			`Content` text NOT NULL,
	// 			PRIMARY KEY (`Id`)
	// 		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	// 		parent::setUpBeforeClass();
	// 	}

	// 	public static function tearDownAfterClass(): void {
	// 		MlDbConn::executeRawQueryStatic("DROP TABLE IF EXISTS `test3`");
	// 		MlDbConn::executeRawQueryStatic("DROP TABLE IF EXISTS `test3_langs`");
	// 		MlDbConn::executeRawQueryStatic("DROP TABLE IF EXISTS `test4`");
	// 		MlDbConn::executeRawQueryStatic("DROP TABLE IF EXISTS `test4_langs`");

	// 		parent::tearDownAfterClass();
	// 	}

	// 	public function setUp(): void {
	// 		self::cleanUp();

	// 		parent::setUp();
	// 	}

	// 	public function tearDown(): void {
	// 		self::cleanUp();

	// 		parent::tearDown();
	// 	}

	// 	private static function cleanUp(): void {
	// 		DbTestUtils::truncateTable('test3');
	// 		DbTestUtils::truncateTable('test3_langs');
	// 		DbTestUtils::truncateTable('test4');
	// 		DbTestUtils::truncateTable('test4_langs');
	// 	}

	// 	public function testSaveWithoutLangSuccess(): void {
	// 		$test = new TestController3();
	// 		$row = $test->save([
	// 			'Title' => 'Test Title',
	// 			'Content' => 'Test Content'
	// 		]);

	// 		$this->assertEquals('Test Title', $row['Title']);
	// 		$this->assertEquals('Test Content', $row['Content']);
	// 	}

	// 	public function testSaveWithLangSuccess(): void {
	// 		$test = new TestController3();
	// 		$row = $test->save([
	// 			'Active' => 1,
	// 			'langs' => [
	// 				'en' => [
	// 					'Title' => 'Test Title EN',
	// 					'Content' => 'Test Content EN'
	// 				],
	// 				'fr' => [
	// 					'Title' => 'Test Title FR',
	// 					'Content' => 'Test Content FR'
	// 				]
	// 			]
	// 		]);

	// 		$this->assertEquals(1, $row['Active']);
	// 		$this->assertEquals('Test Title EN', $row['Title']);
	// 		$this->assertEquals('Test Content EN', $row['Content']);
	// 		$this->assertEquals('Test Title FR', $row['langs']['fr']['Title']);
	// 		$this->assertEquals('Test Content FR', $row['langs']['fr']['Content']);

	// 		$test = new TestController4();
	// 		$row = $test->save([
	// 			'Active' => 1,
	// 			'langs' => [
	// 				'en' => [
	// 					'Title' => 'Test Title EN',
	// 					'Content' => 'Test Content EN'
	// 				],
	// 				'fr' => [
	// 					'Title' => 'Test Title FR',
	// 					'Content' => 'Test Content FR'
	// 				]
	// 			]
	// 		]);

	// 		$this->assertEquals(1, $row['Active']);
	// 		$this->assertEquals('Test Title EN', $row['Title']);
	// 		$this->assertEquals('Test Content EN', $row['langs']['en']['Content']);
	// 		$this->assertEquals('Test Title FR', $row['langs']['fr']['Title']);
	// 		$this->assertEquals('Test Content FR', $row['langs']['fr']['Content']);
	// 	}

	// 	public function testSelectFromDbSuccess(): void {
	// 		$id = DbTestUtils::insertIntoTable(
	// 			[
	// 				'Title' => 'Test Title 1',
	// 				'Content' => 'Test Content 1'
	// 			],
	// 			TestController3::class
	// 		);
	// 		MlDbConn::executeRawQueryStatic("INSERT INTO `test3_langs` (`MainId`, `Lang`, `Title`, `Content`) VALUES ($id, 'fr', 'Test Title 1 Fr', 'Test Content 1 FR')");

	// 		$test = new TestController3();
	// 		$test->selectFromDB();
	// 		$this->assertCount(1, $test->data);
	// 		$this->assertEquals('Test Title 1', $test->data[0]['Title']);
	// 		$this->assertEquals('Test Content 1', $test->data[0]['Content']);
	// 		$this->assertEquals('Test Title 1 Fr', $test->data[0]['langs']['fr']['Title']);
	// 		$this->assertEquals('Test Content 1 FR', $test->data[0]['langs']['fr']['Content']);

	// 		$id = DbTestUtils::insertIntoTable(
	// 			[
	// 				'Title' => 'Test Title 2 En',
	// 			],
	// 			TestController4::class
	// 		);

	// 		MlDbConn::executeRawQueryStatic("INSERT INTO `test4_langs` (`MainId`, `Lang`, `Title`, `Content`) VALUES ($id, 'fr', 'Test Title 2 Fr', 'Test Content 2 FR')");
	// 		MlDbConn::executeRawQueryStatic("INSERT INTO `test4_langs` (`MainId`, `Lang`, `Title`, `Content`) VALUES ($id, 'en', 'Test Title 2 En', 'Test Content 2 En')");

	// 		$test = new TestController4();
	// 		$test->selectFromDB();
	// 		$this->assertCount(1, $test->data);
	// 		$this->assertEquals('Test Title 2 En', $test->data[0]['Title']);
	// 		$this->assertEquals('Test Title 2 Fr', $test->data[0]['langs']['fr']['Title']);
	// 		$this->assertEquals('Test Content 2 FR', $test->data[0]['langs']['fr']['Content']);
	// 		$this->assertEquals('Test Content 2 En', $test->data[0]['langs']['en']['Content']);
	// 	}
	// }

	// class TestController3 extends MlDbConn {
	// 	protected $table = 'test3';
	// 	protected $primaryKey = 'Id';

	// 	protected string $langTable = 'test3_langs';
	// 	protected array $columnsNames = [
	// 		'Title',
	// 		'Content'
	// 	];
	// 	protected string $langKey = 'Lang';
	// 	protected string $mainIdKey = 'MainId';

	// 	public $timestamps = false;

	// 	protected $hidden = [];
	// 	protected $fillable = [
	// 		'Title',
	// 		'Content',
	// 		'Active',
	// 		'Deleted',
	// 		'DeletedDate',
	// 		'CreatedOn',
	// 		'LastUpdated'
	// 	];
	// }

	// class TestController4 extends MlDbConn {
	// 	protected $table = 'test4';
	// 	protected $primaryKey = 'Id';

	// 	protected string $langTable = 'test4_langs';
	// 	protected array $columnsNames = [
	// 		'Title',
	// 		'Content'
	// 	];
	// 	protected string $langKey = 'Lang';
	// 	protected string $mainIdKey = 'MainId';

	// 	public $timestamps = false;

	// 	protected $hidden = [];
	// 	protected $fillable = [
	// 		'Title',
	// 		'Active',
	// 		'Deleted',
	// 		'DeletedDate',
	// 		'CreatedOn',
	// 		'LastUpdated'
	// 	];
	// }