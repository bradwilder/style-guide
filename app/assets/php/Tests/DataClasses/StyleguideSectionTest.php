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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideSection.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideSubsection.class.php';

final class StyleguideSectionTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_section';
	
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
		$this->db->query('delete from sg_subsection');
	}
	
    public function testConstructorEmpty()
    {
		$new = new StyleguideSection($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $new->position);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new StyleguideSection($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new StyleguideSection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set name = "hello", enabled = 1, userCreated = 0 where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new StyleguideSection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('hello', $new->name);
		$this->assertTrue($new->enabled);
		$this->assertFalse($new->userCreated);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSubsection1 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection1));
		$query = 'update sg_subsection set sectionID = ' . $insertedID . ' where id = ' . $insertedSubsection1;
		$this->db->query($query);
		
		$insertedSubsection2 = $this->insertSubsection();
		$this->assertTrue(isset($insertedSubsection2));
		$query = 'update sg_subsection set sectionID = ' . $insertedID . ' where id = ' . $insertedSubsection2;
		$this->db->query($query);
		
		$new = new StyleguideSection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->readExtra();
		$this->assertEquals(2, count($new->subsections));
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideSection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'hello';
		$new->enabled = true;
		$new->userCreated = false;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['name']);
		$this->assertEquals(1, $row['enabled']);
		$this->assertEquals(0, $row['userCreated']);
	}
	
	public function testPosition()
    {
		$new1 = new StyleguideSection($this->db);
		$this->assertNotNull($new1->id);
		
		$new2 = new StyleguideSection($this->db);
		$this->assertNotNull($new2->id);
		
		$new3 = new StyleguideSection($this->db);
		$this->assertNotNull($new3->id);
		
		$new4 = new StyleguideSection($this->db);
		$this->assertNotNull($new4->id);
		
		$copy1 = new StyleguideSection($this->db, $new1->id);
		$copy1->read();
		$this->assertEquals(1, $copy1->position);
		
		$copy2 = new StyleguideSection($this->db, $new2->id);
		$copy2->read();
		$this->assertEquals(2, $copy2->position);
		
		$copy3 = new StyleguideSection($this->db, $new3->id);
		$copy3->read();
		$this->assertEquals(3, $copy3->position);
		
		$copy4 = new StyleguideSection($this->db, $new4->id);
		$copy4->read();
		$this->assertEquals(4, $copy4->position);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideSection($this->db, $insertedID);
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
}

?>