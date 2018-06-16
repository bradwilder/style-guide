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
require_once __ASSETS_PATH . '/php/DataClasses/MoodboardImage.class.php';

final class MoodboardImageTest extends TestCase
{
	private $db;
	private static $tableName = 'mb_image';
	
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
		$this->db->query('delete from mb_section');
	}
	
    public function testConstructorEmpty()
    {
		$new = new MoodboardImage($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new MoodboardImage($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new MoodboardImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set name = "hello", description = "Monkey" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new MoodboardImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('hello', $new->name);
		$this->assertEquals('Monkey', $new->description);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSection = $this->insertSection();
		$this->assertTrue(isset($insertedSection));
		$query = 'update mb_section set name = "derp" where id = ' . $insertedSection;
		$this->db->query($query);
		
		$insertedSectionImage = $this->insertSectionImage($insertedSection, $insertedID);
		$this->assertTrue(isset($insertedSectionImage));
		
		$new = new MoodboardImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->readExtra();
		$this->assertEquals(1, count($new->sections));
		$this->assertEquals('derp', $new->sections[0]['name']);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new MoodboardImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'hello';
		$new->description = 'Monkey';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['name']);
		$this->assertEquals('Monkey', $row['description']);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set description = "Monkey" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new MoodboardImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		
		$new->description = '';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertNull($row['description']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new MoodboardImage($this->db, $insertedID);
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
	
	private function insertSectionImage($sectionID, $imageID)
	{
		$query = 'insert into mb_section_image (sectionID, imageID) values (' . $sectionID . ', ' . $imageID . ')';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertSection()
	{
		$query = 'insert into mb_section () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>