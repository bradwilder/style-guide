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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideIconTableListing.class.php';

final class StyleguideIconTableListingTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_icon_listing';
	
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
		$this->db->query('delete from sg_icon_listing_table');
		$this->db->query('delete from sg_item');
	}
	
    public function testConstructorEmpty()
    {
		$new = new StyleguideIconTableListing($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new StyleguideIconTableListing($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new StyleguideIconTableListing($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insertIconTableItem($insertedItem);
		
		$query = 'update ' . self::$tableName . ' set html = "Monkey", itemID = ' . $insertedItem . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new StyleguideIconTableListing($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('Monkey', $new->html);
		$this->assertEquals($insertedItem, $new->itemID);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insertIconTableItem($insertedItem);
		
		$new = new StyleguideIconTableListing($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->html = 'Monkey';
		$new->itemID = $insertedItem;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('Monkey', $row['html']);
		$this->assertEquals($insertedItem, $row['itemID']);
	}
	
	public function testPosition()
    {
		$insertedItem1 = $this->insertItem();
		$this->assertTrue(isset($insertedItem1));
		
		$insertedIconTableItem1 = $this->insertIconTableItem($insertedItem1);
		$this->assertTrue(isset($insertedIconTableItem1));
		
		$insertedItem2 = $this->insertItem();
		$this->assertTrue(isset($insertedItem2));
		
		$insertedIconTableItem2 = $this->insertIconTableItem($insertedItem2);
		$this->assertTrue(isset($insertedIconTableItem2));
		
		$new1 = new StyleguideIconTableListing($this->db, null, $insertedItem1);
		$this->assertNotNull($new1->id);
		
		$new2 = new StyleguideIconTableListing($this->db, null, $insertedItem2);
		$this->assertNotNull($new2->id);
		
		$new3 = new StyleguideIconTableListing($this->db, null, $insertedItem2);
		$this->assertNotNull($new3->id);
		
		$new4 = new StyleguideIconTableListing($this->db, null, $insertedItem1);
		$this->assertNotNull($new4->id);
		
		$copy1 = new StyleguideIconTableListing($this->db, $new1->id);
		$copy1->read();
		$this->assertEquals(1, $copy1->position);
		$this->assertEquals($insertedItem1, $copy1->itemID);
		
		$copy2 = new StyleguideIconTableListing($this->db, $new2->id);
		$copy2->read();
		$this->assertEquals(1, $copy2->position);
		$this->assertEquals($insertedItem2, $copy2->itemID);
		
		$copy3 = new StyleguideIconTableListing($this->db, $new3->id);
		$copy3->read();
		$this->assertEquals(2, $copy3->position);
		$this->assertEquals($insertedItem2, $copy3->itemID);
		
		$copy4 = new StyleguideIconTableListing($this->db, $new4->id);
		$copy4->read();
		$this->assertEquals(2, $copy4->position);
		$this->assertEquals($insertedItem1, $copy4->itemID);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideIconTableListing($this->db, $insertedID);
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
	
	private function insertItem()
	{
		$query = 'insert into sg_item () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertIconTableItem($id)
	{
		$query = 'insert into sg_icon_listing_table (baseID) values (' . $id . ')';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>