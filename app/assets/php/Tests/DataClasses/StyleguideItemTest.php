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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideItemType.class.php';

final class StyleguideItemTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_item';
	
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
		$this->db->query('delete from sg_color_item');
		$this->db->query('delete from sg_subsection');
	}
	
    public function testConstructorNoID()
    {
		// No id, code, or sub table
		$new = new StyleguideItem($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertNull($new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
		
		// No id, with code and sub table
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_item_type set code = "boxcar" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$new = new StyleguideItem($this->db, null, 'boxcar', 'sg_color_item');
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedItemType, $new->typeID);
		$this->assertEquals(2, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
		$this->assertEquals($new->typeID, $row['typeID']);
		$this->assertEquals($new->typeID, $insertedItemType);
		
		$query = 'select count(*) as count from sg_color_item';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
		
		// No id, with code but no sub table
		$new = new StyleguideItem($this->db, null, 'boxcar');
		$this->assertTrue(!isset($new->id));
		$this->assertEquals(2, $this->getTableCount());
		
		$query = 'select count(*) as count from sg_color_item';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
		
		// No id or code, but with sub table
		$new = new StyleguideItem($this->db, null, null, 'sg_color_item');
		$this->assertTrue(!isset($new->id));
		$this->assertEquals(2, $this->getTableCount());
		
		$query = 'select count(*) as count from sg_color_item';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new StyleguideItem($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new StyleguideItem($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSubsection = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection));
		
		$query = 'update ' . self::$tableName . ' set name = "Dinosaur", colLg = 1, colMd = 2, colSm = 3, colXs = 4, subsectionID = ' . $insertedSubsection . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new StyleguideItem($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('Dinosaur', $new->name);
		$this->assertEquals($insertedSubsection, $new->subsectionID);
		$this->assertEquals(1, $new->colLg);
		$this->assertEquals(2, $new->colMd);
		$this->assertEquals(3, $new->colSm);
		$this->assertEquals(4, $new->colXs);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItemType = $this->insertItemType();
		$this->assertTrue(isset($insertedItemType));
		
		$new = new StyleguideItem($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->typeID = $insertedItemType;
		$new->readExtra();
		$this->assertEquals($insertedItemType, $new->type->id);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSubsection = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection));
		
		$new = new StyleguideItem($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'Dinosaur';
		$new->subsectionID = $insertedSubsection;
		$new->colLg = 1;
		$new->colMd = 2;
		$new->colSm = 3;
		$new->colXs = 4;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('Dinosaur', $row['name']);
		$this->assertEquals($insertedSubsection, $row['subsectionID']);
		$this->assertEquals(1, $row['colLg']);
		$this->assertEquals(2, $row['colMd']);
		$this->assertEquals(3, $row['colSm']);
		$this->assertEquals(4, $row['colXs']);
	}
	
	public function testPosition()
    {
		$insertedSubsection1 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection1));
		
		$insertedSubsection2 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection2));
		
		$new1 = new StyleguideItem($this->db, null, null, null, $insertedSubsection1);
		$this->assertNotNull($new1->id);
		
		$new2 = new StyleguideItem($this->db, null, null, null, $insertedSubsection2);
		$this->assertNotNull($new2->id);
		
		$new3 = new StyleguideItem($this->db, null, null, null, $insertedSubsection2);
		$this->assertNotNull($new3->id);
		
		$new4 = new StyleguideItem($this->db, null, null, null, $insertedSubsection1);
		$this->assertNotNull($new4->id);
		
		$copy1 = new StyleguideItem($this->db, $new1->id);
		$copy1->read();
		$this->assertEquals(1, $copy1->position);
		$this->assertEquals($insertedSubsection1, $copy1->subsectionID);
		
		$copy2 = new StyleguideItem($this->db, $new2->id);
		$copy2->read();
		$this->assertEquals(1, $copy2->position);
		$this->assertEquals($insertedSubsection2, $copy2->subsectionID);
		
		$copy3 = new StyleguideItem($this->db, $new3->id);
		$copy3->read();
		$this->assertEquals(2, $copy3->position);
		$this->assertEquals($insertedSubsection2, $copy3->subsectionID);
		
		$copy4 = new StyleguideItem($this->db, $new4->id);
		$copy4->read();
		$this->assertEquals(2, $copy4->position);
		$this->assertEquals($insertedSubsection1, $copy4->subsectionID);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideItem($this->db, $insertedID);
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
	
	private function insertSubsection()
	{
		$query = 'insert into sg_subsection () values ()';
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