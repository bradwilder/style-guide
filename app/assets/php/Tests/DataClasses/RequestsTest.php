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
require_once __ASSETS_PATH . '/php/DataClasses/Requests.class.php';

final class RequestsTest extends TestCase
{
	private $db;
	private static $tableName = 'requests';
	
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
	}
	
    public function testConstructorEmpty()
    {
		$new = new Requests($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new Requests($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new Requests($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUser = $this->insertUser();
		$this->assertTrue(isset($insertedUser));
		
		$query = 'update ' . self::$tableName . ' set userID = ' . $insertedUser . ', emailKey = "12345678901234567890", smsKey = "09876543210987654321", expire = "2018-01-14 20:26:40", type = "activation" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Requests($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals($insertedUser, $new->userID);
		$this->assertEquals('12345678901234567890', $new->emailKey);
		$this->assertEquals('09876543210987654321', $new->smsKey);
		$this->assertEquals(1515986800, $new->expire);
		$this->assertEquals('activation', $new->type);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUser = $this->insertUser();
		$this->assertTrue(isset($insertedUser));
		
		$new = new Requests($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->userID = $insertedUser;
		$new->emailKey = '12345678901234567890';
		$new->smsKey = '09876543210987654321';
		$new->expire = 1515986800;
		$new->type = 'activation';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals($insertedUser, $row['userID']);
		$this->assertEquals('12345678901234567890', $row['emailKey']);
		$this->assertEquals('09876543210987654321', $row['smsKey']);
		$this->assertEquals('2018-01-14 20:26:40', $row['expire']);
		$this->assertEquals('activation', $row['type']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new Requests($this->db, $insertedID);
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
	
	private function insertUser()
	{
		$query = 'insert into users () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>