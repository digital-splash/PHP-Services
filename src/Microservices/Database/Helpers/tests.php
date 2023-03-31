<?php



use DigitalSplash\Database\Models\DatabaseCredentials;
use DigitalSplash\Database\Helpers\QueryBuilder;
use DigitalSplash\Helpers\Helper;

class Tests{

    public function testInsert() {
        $table = 'users';
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '123456'
        ];
        $db = new DatabaseCredentials('localhost', 'root', '', 'test');
        $sql = QueryBuilder::insert($table, $data, $db);
        $expectedSql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $this->assertEquals($expectedSql, $sql);
    }

    public function testUpdate() {
        $table = 'users';
        $data = [
            'name' => 'John',
            'age' => 25
        ];
        $where = 'id = 1';
        $db = new DatabaseCredentials('localhost', 'root', 'password', 'test');
        $expectedSql = "UPDATE users SET (name, age) VALUES (?, ?) WHERE id = 1";
        $actualSql = QueryBuilder::update($table, $data, $where, $db);
        $this->assertEquals($expectedSql, $actualSql);
    }

    public function testDelete() {
        $table = 'users';
        $where = 'id=1';
        $db = new DatabaseCredentials('localhost', 'username', 'password', 'database');
        $expected = "DELETE FROM $table WHERE $where";
        $actual = QueryBuilder::delete($table, $where, $db);
        $this->assertEquals($expected, $actual);
    }

    public function testSelect() {
        $table = 'users';
        $columns = ['name', 'email'];
        $where = 'id = 1';
        $db = new DatabaseCredentials('localhost', 'root', 'password', 'test_db');

        $expectedSql = "SELECT name, email FROM users WHERE id = 1";
        $actualSql = QueryBuilder::select($table, $columns, $where, $db);

        $this->assertEquals($expectedSql, $actualSql);
    }

    public function testSelectAll() {
        $table = 'users';
        $db = new DatabaseCredentials('localhost', 'root', 'password', 'test_db');
        $expected = "SELECT * FROM $table";
        $actual = QueryBuilder::selectAll($table, $db);
        $this->assertEquals($expected, $actual);
    }

    public function testSelectAllWhere() {
        $table = 'users';
        $where = ['name' => 'John', 'age' => 25];
        $db = new DatabaseCredentials('localhost', 'root', 'password', 'test_db');
        $expectedSql = "SELECT * FROM users WHERE name='John' AND age=25";
        $actualSql = QueryBuilder::selectAllWhere($table, $where, $db);
        $this->assertEquals($expectedSql, $actualSql);
    }


    public function testSelectAllOrderBy() {
        $table = 'users';
        $db = new DatabaseCredentials('localhost', 'root', 'password', 'test_db');
        $orderBy = 'name';
        $expectedSql = "SELECT * FROM $table ORDER BY $orderBy";
        $actualSql = QueryBuilder::selectAllOrderBy($table, $db, $orderBy);
        $this->assertEquals($expectedSql, $actualSql);
    }


    public function testSelectAllWhereOrderBy() {
        $table = 'users';
        $where = ['name' => 'John', 'age' => 25];
        $db = new DatabaseCredentials('localhost', 'root', 'password', 'test_db');
        $orderBy = 'name';
        $expectedSql = "SELECT * FROM users WHERE name='John' AND age=25 ORDER BY name";
        $actualSql = QueryBuilder::selectAllWhereOrderBy($table, $where, $db, $orderBy);
        $this->assertEquals($expectedSql, $actualSql);
    }


    




}
