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
require_once __ASSETS_PATH . '/php/DataClasses/Color.class.php';

final class ColorTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_color';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->db = new Db('test');
	}
	
	/**
     * @before
	 * @after
     */
	public function clear()
	{
		$this->db->query('delete from ' . self::$tableName);
		$this->db->query('delete from sg_item');
		$this->db->query('delete from sg_color_item');
	}
	
    public function testConstructorEmpty()
    {
		$new = new Color($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new Color($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new Color($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set name = "hello", hex = "abcdef", variant1 = "4beef4" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Color($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('hello', $new->name);
		$this->assertEquals('abcdef', $new->hex);
		$this->assertEquals('4beef4', $new->variant1);
		$this->assertNull($new->variant2);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new Color($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'hello';
		$new->hex = 'abcdef';
		$new->variant1 = '4beef4';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['name']);
		$this->assertEquals('abcdef', $row['hex']);
		$this->assertEquals('4beef4', $row['variant1']);
		$this->assertNull($row['variant2']);
	}
	
	public function testWriteVariant2NoVariant1()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new Color($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'hello';
		$new->hex = 'abcdef';
		$new->variant2 = '4beef4';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['name']);
		$this->assertEquals('abcdef', $row['hex']);
		$this->assertEquals('4beef4', $row['variant1']);
		$this->assertNull($row['variant2']);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set variant1 = "4beef4", variant2 = "eeffee" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Color($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		
		$new->variant1 = '';
		$new->variant2 = '';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertNull($row['variant1']);
		$this->assertNull($row['variant2']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insertColorItem($insertedItem);
		$query = 'update sg_color_item set color1ID = ' . $insertedID . ' where baseID = ' . $insertedItem;
		$this->db->query($query);
		
		$new = new Color($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->delete();
		$this->assertEquals(0, $this->getTableCount());
		$this->assertEquals(0, $this->getTableCount('sg_item'));
		$this->assertEquals(0, $this->getTableCount('sg_color_item'));
	}
	
	private function getTableCount($table = null)
	{
		$query = 'select count(*) as count from ' . ($table ? $table : self::$tableName);
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
	
	private function insertItem()
	{
		$query = 'insert into sg_item () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertColorItem($itemID)
	{
		$query = 'insert into sg_color_item (baseID) values (' . $itemID . ')';
		$this->db->query($query);
		$this->db->insert_id();
	}
}

?>