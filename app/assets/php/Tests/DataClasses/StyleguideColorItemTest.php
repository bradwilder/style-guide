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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideColorItem.class.php';

final class StyleguideColorItemTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_color_item';
	
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
		$this->db->query('delete from sg_color');
		$this->db->query('delete from sg_color_descriptor');
	}
	
    public function testConstructorNoID()
    {
		// No id or code
		$new = new StyleguideColorItem($this->db);
		$this->assertTrue(!isset($new->id));
		$this->assertEquals(0, $this->getTableCount());
		
		// No id but with code
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "boxcar" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$new = new StyleguideColorItem($this->db, null, 'boxcar');
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedItemType, $new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['baseID']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new StyleguideColorItem($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "boxcar" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideColorItem($this->db, $insertedItem, 'boxcar');
		$this->assertEquals($insertedItem, $new->id);
	}
	
	public function testRead()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "boxcar" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedColor1 = $this->insertColor();
		$this->assertTrue(isset($insertedColor1));
		
		$insertedColor2 = $this->insertColor();
		$this->assertTrue(isset($insertedColor2));
		
		$insertedColor3 = $this->insertColor();
		$this->assertTrue(isset($insertedColor3));
		
		$query = 'update ' . self::$tableName . ' set color1ID = ' . $insertedColor1 . ', color2ID = ' . $insertedColor2 . ', color3ID = ' . $insertedColor3 . ' where baseID = ' . $insertedItem;
		$this->db->query($query);
		
		$new = new StyleguideColorItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
		$new->read();
		$this->assertEquals($insertedColor1, $new->color1ID);
		$this->assertEquals($insertedColor2, $new->color2ID);
		$this->assertEquals($insertedColor3, $new->color3ID);
		$this->assertNull($new->color4ID);
		$this->assertNull($new->color5ID);
		$this->assertNull($new->color6ID);
	}
	
	public function testReadItemData()
	{
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "boxcar" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedColor1 = $this->insertColor();
		$this->assertTrue(isset($insertedColor1));
		
		$insertedColor2 = $this->insertColor();
		$this->assertTrue(isset($insertedColor2));
		
		$insertedColor3 = $this->insertColor();
		$this->assertTrue(isset($insertedColor3));
		
		$insertedDescriptor1 = $this->insertColorDescriptor();
		$this->assertTrue(isset($insertedDescriptor1));
		$query = 'update sg_color_descriptor set itemID = ' . $insertedItem . ' where id = ' . $insertedDescriptor1;
		$this->db->query($query);
		
		$insertedDescriptor2 = $this->insertColorDescriptor();
		$this->assertTrue(isset($insertedDescriptor2));
		$query = 'update sg_color_descriptor set itemID = ' . $insertedItem . ' where id = ' . $insertedDescriptor2;
		$this->db->query($query);
		
		$new = new StyleguideColorItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		$new->color1ID = $insertedColor1;
		$new->color2ID = $insertedColor2;
		$new->color3ID = $insertedColor3;
		$new->readItemData();
		$this->assertEquals(3, count($new->colors));
		$this->assertEquals(2, count($new->descriptors));
	}
	
	public function testWrite()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "boxcar" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedColor1 = $this->insertColor();
		$this->assertTrue(isset($insertedColor1));
		
		$insertedColor2 = $this->insertColor();
		$this->assertTrue(isset($insertedColor2));
		
		$insertedColor3 = $this->insertColor();
		$this->assertTrue(isset($insertedColor3));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideColorItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
		$new->color1ID = $insertedColor1;
		$new->color2ID = $insertedColor2;
		$new->color3ID = $insertedColor3;
		$new->color4ID = 0;
		$new->color5ID = 0;
		$new->color6ID = 0;
		$new->write();
		$row = $this->getByID($insertedItem);
		$this->assertEquals($insertedColor1, $row['color1ID']);
		$this->assertEquals($insertedColor2, $row['color2ID']);
		$this->assertEquals($insertedColor3, $row['color3ID']);
		$this->assertNull($row['color4ID']);
		$this->assertNull($row['color5ID']);
		$this->assertNull($row['color6ID']);
	}
	
	public function testWriteNull()
	{
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "boxcar" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedColor1 = $this->insertColor();
		$this->assertTrue(isset($insertedColor1));
		
		$insertedColor2 = $this->insertColor();
		$this->assertTrue(isset($insertedColor2));
		
		$insertedColor3 = $this->insertColor();
		$this->assertTrue(isset($insertedColor3));
		
		$insertedColor4 = $this->insertColor();
		$this->assertTrue(isset($insertedColor4));
		
		$insertedColor5 = $this->insertColor();
		$this->assertTrue(isset($insertedColor5));
		
		$insertedColor6 = $this->insertColor();
		$this->assertTrue(isset($insertedColor6));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set color1ID = ' . $insertedColor1 . ', color2ID = ' . $insertedColor2 . ', color3ID = ' . $insertedColor3 . ', color4ID = ' . $insertedColor4 . ', color5ID = ' . $insertedColor5 . ', color6ID = ' . $insertedColor6 . ' where baseID = ' . $insertedItem;
		$this->db->query($query);
		
		$new = new StyleguideColorItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
		$new->read();
		
		$new->color1ID = 0;
		$new->color2ID = 0;
		$new->color3ID = 0;
		$new->color4ID = 0;
		$new->color5ID = 0;
		$new->color6ID = 0;
		$new->write();
		$row = $this->getByID($insertedItem);
		$this->assertNull($row['color1ID']);
		$this->assertNull($row['color2ID']);
		$this->assertNull($row['color3ID']);
		$this->assertNull($row['color4ID']);
		$this->assertNull($row['color5ID']);
		$this->assertNull($row['color6ID']);
	}
	
	public function testDelete()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "boxcar" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideColorItem($this->db, $insertedItem, 'boxcar');
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
	
	private function insertColor()
	{
		$query = 'insert into sg_color () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertColorDescriptor()
	{
		$query = 'insert into sg_color_descriptor () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>