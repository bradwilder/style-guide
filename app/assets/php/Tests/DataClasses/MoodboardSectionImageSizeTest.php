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
require_once __ASSETS_PATH . '/php/DataClasses/MoodboardSectionImageSize.class.php';

final class MoodboardSectionImageSizeTest extends TestCase
{
	private $db;
	private static $tableName = 'mb_size';
	
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
		$new = new MoodboardSectionImageSize($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new MoodboardSectionImageSize($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new MoodboardSectionImageSize($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set name = "hello", code = "turkey", description = "Monkey" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new MoodboardSectionImageSize($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('hello', $new->name);
		$this->assertEquals('turkey', $new->code);
		$this->assertEquals('Monkey', $new->description);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new MoodboardSectionImageSize($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'hello';
		$new->code = 'turkey';
		$new->description = 'Monkey';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['name']);
		$this->assertEquals('turkey', $row['code']);
		$this->assertEquals('Monkey', $row['description']);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set description = "Monkey" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new MoodboardSectionImageSize($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		
		$new->description = '';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertNull($row['description']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new MoodboardSectionImageSize($this->db, $insertedID);
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
}

?>