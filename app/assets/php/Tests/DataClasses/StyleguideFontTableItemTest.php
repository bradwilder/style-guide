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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideFontTableItem.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideFontTableListing.class.php';

final class StyleguideFontTableItemTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_font_listing_table';
	
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
		$this->db->query('delete from sg_font_listing');
	}
	
    public function testConstructorNoID()
    {
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "font-tbl" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$new = new StyleguideFontTableItem($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedItemType, $new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['baseID']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new StyleguideFontTableItem($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideFontTableItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
	}
	
	public function testReadItemData()
	{
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedListing1 = $this->insertListing();
		$this->assertTrue(isset($insertedListing1));
		$query = 'update sg_font_listing set itemID = ' . $insertedItem . ' where id = ' . $insertedListing1;
		$this->db->query($query);
		
		$insertedListing2 = $this->insertListing();
		$this->assertTrue(isset($insertedListing2));
		$query = 'update sg_font_listing set itemID = ' . $insertedItem . ' where id = ' . $insertedListing2;
		$this->db->query($query);
		
		$insertedListing3 = $this->insertListing();
		$this->assertTrue(isset($insertedListing3));
		$query = 'update sg_font_listing set itemID = ' . $insertedItem . ' where id = ' . $insertedListing3;
		$this->db->query($query);
		
		$new = new StyleguideFontTableItem($this->db, $insertedItem);
		$this->assertEquals($insertedItem, $new->id);
		$new->readItemData();
		$this->assertEquals(3, count($new->listings));
	}
	
	public function testPosition()
    {
		$insertedSubsection1 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection1));
		
		$insertedSubsection2 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection2));
		
		$new1 = new StyleguideFontTableItem($this->db, null, $insertedSubsection1);
		$this->assertNotNull($new1->id);
		
		$new2 = new StyleguideFontTableItem($this->db, null, $insertedSubsection2);
		$this->assertNotNull($new2->id);
		
		$new3 = new StyleguideFontTableItem($this->db, null, $insertedSubsection2);
		$this->assertNotNull($new3->id);
		
		$new4 = new StyleguideFontTableItem($this->db, null, $insertedSubsection1);
		$this->assertNotNull($new4->id);
		
		$copy1 = new StyleguideFontTableItem($this->db, $new1->id);
		$copy1->read();
		$this->assertEquals(1, $copy1->position);
		$this->assertEquals($insertedSubsection1, $copy1->subsectionID);
		
		$copy2 = new StyleguideFontTableItem($this->db, $new2->id);
		$copy2->read();
		$this->assertEquals(1, $copy2->position);
		$this->assertEquals($insertedSubsection2, $copy2->subsectionID);
		
		$copy3 = new StyleguideFontTableItem($this->db, $new3->id);
		$copy3->read();
		$this->assertEquals(2, $copy3->position);
		$this->assertEquals($insertedSubsection2, $copy3->subsectionID);
		
		$copy4 = new StyleguideFontTableItem($this->db, $new4->id);
		$copy4->read();
		$this->assertEquals(2, $copy4->position);
		$this->assertEquals($insertedSubsection1, $copy4->subsectionID);
	}
	
	public function testDelete()
    {
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insert($insertedItem);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideFontTableItem($this->db, $insertedItem);
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
	
	private function insertListing()
	{
		$query = 'insert into sg_font_listing () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertSubsection()
	{
		$query = 'insert into sg_subsection () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>