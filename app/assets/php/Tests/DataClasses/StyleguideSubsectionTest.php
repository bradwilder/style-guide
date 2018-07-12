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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideSubsection.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideItem.class.php';

final class StyleguideSubsectionTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_subsection';
	
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
		$this->db->query('delete from sg_section');
		$this->db->query('delete from sg_item');
	}
	
    public function testConstructorEmpty()
    {
		$new = new StyleguideSubsection($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new StyleguideSubsection($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new StyleguideSubsection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSection = $this->insertSection();
		$this->assertTrue(isset($insertedSection));
		
		$query = 'update ' . self::$tableName . ' set name = "hello", description = "Monkey", position = 6, enabled = 1, sectionID = ' . $insertedSection . ', parentSubsectionID = ' . $insertedID . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new StyleguideSubsection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('hello', $new->name);
		$this->assertEquals('Monkey', $new->description);
		$this->assertEquals(6, $new->position);
		$this->assertTrue($new->enabled);
		$this->assertEquals($insertedSection, $new->sectionID);
		$this->assertEquals($insertedID, $new->parentSubsectionID);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSection = $this->insertSection();
		$this->assertTrue(isset($insertedSection));
		
		$insertedSubsection1 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection1));
		$query = 'update sg_subsection set sectionID = ' . $insertedSection . ', parentSubsectionID = ' . $insertedID . ', enabled = 1 where id = ' . $insertedSubsection1;
		$this->db->query($query);
		
		$insertedSubsection2 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection2));
		$query = 'update sg_subsection set sectionID = ' . $insertedSection . ', parentSubsectionID = ' . $insertedID . ', enabled = 1 where id = ' . $insertedSubsection2;
		$this->db->query($query);
		
		$insertedSubsection3 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection3));
		$query = 'update sg_subsection set sectionID = ' . $insertedSection . ', parentSubsectionID = ' . $insertedID . ', enabled = 0 where id = ' . $insertedSubsection3;
		$this->db->query($query);
		
		$insertedItem1 = $this->insertItem();
		$this->assertTrue(isset($insertedItem1));
		$query = 'update sg_item set subsectionID = ' . $insertedID . ' where id = ' . $insertedItem1;
		$this->db->query($query);
		
		$insertedItem2 = $this->insertItem();
		$this->assertTrue(isset($insertedItem2));
		$query = 'update sg_item set subsectionID = ' . $insertedID . ' where id = ' . $insertedItem2;
		$this->db->query($query);
		
		$new = new StyleguideSubsection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->sectionID = $insertedSection;
		$new->readExtra();
		$this->assertEquals(3, count($new->subSubsections));
		$this->assertEquals(2, count($new->items));
		
		$new = new StyleguideSubsection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->sectionID = $insertedSection;
		$new->readExtra(true);
		$this->assertEquals(2, count($new->subSubsections));
		$this->assertEquals(2, count($new->items));
		
		$new = new StyleguideSubsection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->sectionID = $insertedSection;
		$new->readExtra(false);
		$this->assertEquals(1, count($new->subSubsections));
		$this->assertEquals(2, count($new->items));
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSection = $this->insertSection();
		$this->assertTrue(isset($insertedSection));
		
		$new = new StyleguideSubsection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'hello';
		$new->description = 'Monkey';
		$new->position = 6;
		$new->enabled = true;
		$new->sectionID = $insertedSection;
		$new->parentSubsectionID = $insertedID;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['name']);
		$this->assertEquals('Monkey', $row['description']);
		$this->assertEquals(6, $row['position']);
		$this->assertEquals(1, $row['enabled']);
		$this->assertEquals($insertedSection, $row['sectionID']);
		$this->assertEquals($insertedID, $row['parentSubsectionID']);
	}
	
	public function testPosition()
    {
		$insertedSection1 = $this->insertSection();
		$this->assertTrue(isset($insertedSection1));
		
		$insertedSection2 = $this->insertSection();
		$this->assertTrue(isset($insertedSection2));
		
		$new1 = new StyleguideSubsection($this->db, null, $insertedSection1);
		$this->assertNotNull($new1->id);
		
		$new2 = new StyleguideSubsection($this->db, null, $insertedSection2);
		$this->assertNotNull($new2->id);
		
		$new3 = new StyleguideSubsection($this->db, null, $insertedSection2);
		$this->assertNotNull($new3->id);
		
		$new4 = new StyleguideSubsection($this->db, null, $insertedSection1);
		$this->assertNotNull($new4->id);
		
		$copy1 = new StyleguideSubsection($this->db, $new1->id);
		$copy1->read();
		$this->assertEquals(1, $copy1->position);
		$this->assertEquals($insertedSection1, $copy1->sectionID);
		
		$copy2 = new StyleguideSubsection($this->db, $new2->id);
		$copy2->read();
		$this->assertEquals(1, $copy2->position);
		$this->assertEquals($insertedSection2, $copy2->sectionID);
		
		$copy3 = new StyleguideSubsection($this->db, $new3->id);
		$copy3->read();
		$this->assertEquals(2, $copy3->position);
		$this->assertEquals($insertedSection2, $copy3->sectionID);
		
		$copy4 = new StyleguideSubsection($this->db, $new4->id);
		$copy4->read();
		$this->assertEquals(2, $copy4->position);
		$this->assertEquals($insertedSection1, $copy4->sectionID);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set parentSubsectionID = ' . $insertedID . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new StyleguideSubsection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		
		$new->parentSubsectionID = 0;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertNull($row['parentSubsectionID']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideSubsection($this->db, $insertedID);
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
	
	private function insertSection()
	{
		$query = 'insert into sg_section () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertItem()
	{
		$query = 'insert into sg_item () values ()';
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