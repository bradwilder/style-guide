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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideFontFamilyItem.class.php';

final class StyleguideFontFamilyItemTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_font_family';
	
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
		$this->db->query('delete from sg_item');
		$this->db->query('delete from sg_font');
	}
	
    public function testConstructorNoID()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "font-fmy" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$new = new StyleguideFontFamilyItem($this->db);
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
		$query = 'update sg_item_type set code = "font-fmy" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$new = new StyleguideFontFamilyItem($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "font-fmy" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideFontFamilyItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
	}
	
	public function testRead()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "font-fmy" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedFont = $this->insertFont();
		$this->assertTrue(isset($insertedFont));
		
		$query = 'update ' . self::$tableName . ' set fontID = ' . $insertedFont . ' where baseID = ' . $insertedItem;
		$this->db->query($query);
		
		$new = new StyleguideFontFamilyItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
		$new->read();
		$this->assertEquals($insertedFont, $new->fontID);
	}
	
	public function testWrite()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "font-fmy" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedFont = $this->insertFont();
		$this->assertTrue(isset($insertedFont));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideFontFamilyItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		
		$new->fontID = $insertedFont;
		$new->write();
		$row = $this->getByID($insertedItem);
		$this->assertEquals($insertedFont, $row['fontID']);
	}
	
	public function testDelete()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "font-fmy" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideFontFamilyItem($this->db, $insertedItem);
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
	
	private function insertFont()
	{
		$query = 'insert into sg_font () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>