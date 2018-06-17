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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideItemTypeColumnMin.class.php';

final class StyleguideItemTypeColunnMinTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_item_type_column_min';
	
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
		$this->db->query('delete from sg_item_type');
	}
	
    public function testConstructorEmpty()
    {
		$new = new StyleguideItemTypeColumnMin($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new StyleguideItemTypeColumnMin($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new StyleguideItemTypeColumnMin($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		
		$query = 'update ' . self::$tableName . ' set typeID = ' . $insertedItemType . ', code = "hello", minLG = 1, minMD = 2, minSM = 3, minXS = 4 where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new StyleguideItemTypeColumnMin($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals($insertedItemType, $new->typeID);
		$this->assertEquals('hello', $new->code);
		$this->assertEquals(1, $new->minLG);
		$this->assertEquals(2, $new->minMD);
		$this->assertEquals(3, $new->minSM);
		$this->assertEquals(4, $new->minXS);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		
		$new = new StyleguideItemTypeColumnMin($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->typeID = $insertedItemType;
		$new->code = 'hello';
		$new->minLG = 1;
		$new->minMD = 2;
		$new->minSM = 3;
		$new->minXS = 4;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals($insertedItemType, $row['typeID']);
		$this->assertEquals('hello', $row['code']);
		$this->assertEquals(1, $row['minLG']);
		$this->assertEquals(2, $row['minMD']);
		$this->assertEquals(3, $row['minSM']);
		$this->assertEquals(4, $row['minXS']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideItemTypeColumnMin($this->db, $insertedID);
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
	
	private function insertItemType()
	{
		$query = 'insert into sg_item_type () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>