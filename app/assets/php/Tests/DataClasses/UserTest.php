<?php

use PHPUnit\Framework\TestCase;

if (!defined('__SITE_PATH'))
{
	$site_path = realpath(__DIR__ . '/../../../..');
	define ('__SITE_PATH', $site_path);
	define ('__ASSETS_PATH', __SITE_PATH . '/assets');
}

require_once __ASSETS_PATH . '/php/DB/Db.php';
require_once __ASSETS_PATH . '/php/DB/DataClassBase.php';
require_once __ASSETS_PATH . '/php/DataClasses/User.class.php';

final class UserTest extends TestCase
{
	private $db;
	private static $tableName = 'users';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->db = new Db();
		$this->db->changeDatabase('test');
	}
	
	/**
     * @before
	 * @after
     */
	public function clear()
	{
		$this->db->query('delete from ' . self::$tableName);
		$this->db->query('delete from groups');
		$this->db->query('delete from requests');
		$this->db->query('delete from sessions');
	}
	
    public function testConstructorEmpty()
    {
		$new = new User($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new User($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new User($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedGroup = $this->insertGroup();
		$this->assertTrue(isset($insertedGroup));
		
		$query = 'update ' . self::$tableName . ' set displayName = "hello", email = "Monkey", phone = "80904", password = "Chicken thigh", isActive = 1, isDeleted = 0, resetNeeded = 1, groupID = ' . $insertedGroup . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new User($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('hello', $new->displayName);
		$this->assertEquals('Monkey', $new->email);
		$this->assertEquals('80904', $new->phone);
		$this->assertEquals('Chicken thigh', $new->password);
		$this->assertTrue($new->isActive);
		$this->assertFalse($new->isDeleted);
		$this->assertTrue($new->resetNeeded);
		$this->assertEquals($insertedGroup, $new->groupID);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedRequest1 = $this->insertRequest();
		$this->assertTrue(isset($insertedRequest1));
		$query = 'update requests set userID = ' . $insertedID . ', emailKey = "key1" where id = ' . $insertedRequest1;
		$this->db->query($query);
		
		$insertedRequest2 = $this->insertRequest();
		$this->assertTrue(isset($insertedRequest2));
		$query = 'update requests set userID = ' . $insertedID . ', emailKey = "key1" where id = ' . $insertedRequest2;
		$this->db->query($query);
		
		$insertedRequest3 = $this->insertRequest();
		$this->assertTrue(isset($insertedRequest3));
		$query = 'update requests set userID = ' . $insertedID . ', emailKey = "key2" where id = ' . $insertedRequest3;
		$this->db->query($query);
		
		$insertedSession1 = $this->insertSession();
		$this->assertTrue(isset($insertedSession1));
		$query = 'update sessions set userID = ' . $insertedID . ' where id = ' . $insertedSession1;
		$this->db->query($query);
		
		$insertedSession2 = $this->insertSession();
		$this->assertTrue(isset($insertedSession2));
		$query = 'update sessions set userID = ' . $insertedID . ' where id = ' . $insertedSession2;
		$this->db->query($query);
		
		$insertedSession3 = $this->insertSession();
		$this->assertTrue(isset($insertedSession3));
		$query = 'update sessions set userID = ' . $insertedID . ' where id = ' . $insertedSession3;
		$this->db->query($query);
		
		$new = new User($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->readExtra();
		$this->assertEquals(2, count($new->requests));
		$this->assertEquals(3, $new->sessions);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedGroup = $this->insertGroup();
		$this->assertTrue(isset($insertedGroup));
		
		$new = new User($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->displayName = 'hello';
		$new->email = 'Monkey';
		$new->phone = '80904';
		$new->password = 'Chicken thigh';
		$new->isActive = true;
		$new->isDeleted = false;
		$new->resetNeeded = true;
		$new->groupID = $insertedGroup;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['displayName']);
		$this->assertEquals('Monkey', $row['email']);
		$this->assertEquals('80904', $row['phone']);
		$this->assertEquals('Chicken thigh', $row['password']);
		$this->assertEquals(1, $row['isActive']);
		$this->assertEquals(0, $row['isDeleted']);
		$this->assertEquals(1, $row['resetNeeded']);
		$this->assertEquals($insertedGroup, $row['groupID']);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set displayName = "hello", phone = "80904" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new User($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		
		$new->displayName = '';
		$new->phone = '';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertNull($row['displayName']);
		$this->assertNull($row['phone']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new User($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->delete();
		$this->assertEquals(0, $this->getTableCount());
	}
	
	private function getTableCount()
	{
		$query = 'select count(*) as count from ' . self::$tableName;
		$rows = $this->db->select($query);
		return $rows[0]['count'];
	}
	
	private function getByID($id)
	{
		$query = 'select * from ' . self::$tableName . ' where id = ' . $id;
		$rows = $this->db->select($query);
		return $rows[0];
	}
	
	private function insert()
	{
		$query = 'insert into ' . self::$tableName . ' () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertGroup()
	{
		$query = 'insert into groups () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertSession()
	{
		$query = 'insert into sessions () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertRequest()
	{
		$query = 'insert into requests () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>