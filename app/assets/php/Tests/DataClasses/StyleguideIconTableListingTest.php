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
	
	public function testWritePosition()
    {
		$insertedID1 = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedID2 = $this->insert();
		$this->assertEquals(2, $this->getTableCount());
		
		$insertedID3 = $this->insert();
		$this->assertEquals(3, $this->getTableCount());
		
		$insertedID4 = $this->insert();
		$this->assertEquals(4, $this->getTableCount());
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insertIconTableItem($insertedItem);
		
		$new1 = new StyleguideIconTableListing($this->db, $insertedID1);
		$this->assertEquals($insertedID1, $new1->id);
		
		$new2 = new StyleguideIconTableListing($this->db, $insertedID2);
		$this->assertEquals($insertedID2, $new2->id);
		
		$new3 = new StyleguideIconTableListing($this->db, $insertedID3);
		$this->assertEquals($insertedID3, $new3->id);
		
		$new4 = new StyleguideIconTableListing($this->db, $insertedID4);
		$this->assertEquals($insertedID4, $new4->id);
		
		$new3->writePosition();
		$this->assertNull($new3->position);
		
		$new1->itemID = $insertedItem;
		$new2->itemID = $insertedItem;
		$new3->itemID = $insertedItem;
		$new4->itemID = $insertedItem;
		
		$new3->writePosition();
		$this->assertEquals(1, $new3->position);
		
		$new2->writePosition();
		$this->assertEquals(2, $new2->position);
		
		$new1->writePosition();
		$this->assertEquals(3, $new1->position);
		
		$new4->writePosition();
		$this->assertEquals(4, $new4->position);
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
	}
}

?>