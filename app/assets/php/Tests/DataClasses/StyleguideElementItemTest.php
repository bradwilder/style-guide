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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideItem.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideElementItem.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/Upload.class.php';

final class StyleguideElementItemTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_element';
	
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
		$this->db->query('delete from sg_item_type');
		$this->db->query('delete from sg_item');
		$this->db->query('delete from sg_upload');
	}
	
    public function testConstructorNoID()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "elem-seg" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$new = new StyleguideElementItem($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedItemType, $new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['baseID']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "elem-seg" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$new = new StyleguideElementItem($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "elem-seg" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideElementItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
	}
	
	public function testRead()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "elem-seg" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUpload1 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload1));
		
		$insertedUpload2 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload2));
		
		$insertedUpload3 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload3));
		
		$insertedUpload4 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload4));
		
		$query = 'update ' . self::$tableName . ' set upload1ID = ' . $insertedUpload1 . ', upload2ID = ' . $insertedUpload2 . ', upload3ID = ' . $insertedUpload3 . ', upload4ID = ' . $insertedUpload4 . ' where baseID = ' . $insertedItem;
		$this->db->query($query);
		
		$new = new StyleguideElementItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
		$new->read();
		$this->assertEquals($insertedUpload1, $new->upload1ID);
		$this->assertEquals($insertedUpload2, $new->upload2ID);
		$this->assertEquals($insertedUpload3, $new->upload3ID);
		$this->assertEquals($insertedUpload4, $new->upload4ID);
		$this->assertNull($new->upload5ID);
		$this->assertNull($new->upload6ID);
	}
	
	public function testReadItemData()
	{
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "elem-seg" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUpload1 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload1));
		
		$insertedUpload2 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload2));
		
		$insertedUpload3 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload3));
		
		$insertedUpload4 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload4));
		
		$new = new StyleguideElementItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		$new->upload1ID = $insertedUpload1;
		$new->upload2ID = $insertedUpload2;
		$new->upload3ID = $insertedUpload3;
		$new->upload4ID = $insertedUpload4;
		$new->readItemData();
		$this->assertEquals(4, count($new->uploads));
	}
	
	public function testWrite()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "elem-seg" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUpload1 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload1));
		
		$insertedUpload2 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload2));
		
		$insertedUpload3 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload3));
		
		$insertedUpload4 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload4));
		
		$new = new StyleguideElementItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
		$new->upload1ID = $insertedUpload1;
		$new->upload2ID = $insertedUpload2;
		$new->upload3ID = $insertedUpload3;
		$new->upload4ID = $insertedUpload4;
		$new->write();
		$row = $this->getByID($insertedItem);
		$this->assertEquals($insertedUpload1, $row['upload1ID']);
		$this->assertEquals($insertedUpload2, $row['upload2ID']);
		$this->assertEquals($insertedUpload3, $row['upload3ID']);
		$this->assertEquals($insertedUpload4, $row['upload4ID']);
		$this->assertNull($row['upload5ID']);
		$this->assertNull($row['upload6ID']);
	}
	
	public function testWriteNull()
	{
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "elem-seg" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUpload1 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload1));
		
		$insertedUpload2 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload2));
		
		$insertedUpload3 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload3));
		
		$insertedUpload4 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload4));
		
		$insertedUpload5 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload5));
		
		$insertedUpload6 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload6));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set upload1ID = ' . $insertedUpload1 . ', upload2ID = ' . $insertedUpload2 . ', upload3ID = ' . $insertedUpload3 . ', upload4ID = ' . $insertedUpload4 . ', upload5ID = ' . $insertedUpload5 . ', upload6ID = ' . $insertedUpload6 . ' where baseID = ' . $insertedItem;
		$this->db->query($query);
		
		$new = new StyleguideElementItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
		$new->read();
		
		$new->upload1ID = 0;
		$new->upload2ID = 0;
		$new->upload3ID = 0;
		$new->upload4ID = 0;
		$new->upload5ID = 0;
		$new->upload6ID = 0;
		$new->write();
		$row = $this->getByID($insertedItem);
		$this->assertNull($row['upload1ID']);
		$this->assertNull($row['upload2ID']);
		$this->assertNull($row['upload3ID']);
		$this->assertNull($row['upload4ID']);
		$this->assertNull($row['upload5ID']);
		$this->assertNull($row['upload6ID']);
	}
	
	public function testDelete()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "elem-seg" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideElementItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
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
		$query = 'select * from ' . self::$tableName . ' where baseID = ' . $id;
		$rows = $this->db->select($query);
		return $rows[0];
	}
	
	private function insert($id)
	{
		$query = 'insert into ' . self::$tableName . ' (baseID) values (' . $id . ')';
		$this->db->query($query);
	}
	
	private function insertItem()
	{
		$query = 'insert into sg_item () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertItemType()
	{
		$query = 'insert into sg_item_type () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertUpload()
	{
		$query = 'insert into sg_upload () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>