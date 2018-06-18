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
require_once __ASSETS_PATH . '/php/DataClasses/MoodboardSection.class.php';

final class MoodboardSectionTest extends TestCase
{
	private $db;
	private static $tableName = 'mb_section';
	
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
		$this->db->query('delete from mb_mode');
		$this->db->query('delete from mb_section_image');
		$this->db->query('delete from mb_image');
	}
	
    public function testConstructorEmpty()
    {
		$new = new MoodboardSection($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $new->position);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new MoodboardSection($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new MoodboardSection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedMode = $this->insertMode();
		$this->assertTrue(isset($insertedMode));
		
		$query = 'update ' . self::$tableName . ' set name = "hello", description = "Monkey", modeID = ' . $insertedMode . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new MoodboardSection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('hello', $new->name);
		$this->assertEquals('Monkey', $new->description);
		$this->assertEquals($insertedMode, $new->modeID);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedMode = $this->insertMode();
		$this->assertTrue(isset($insertedMode));
		$query = 'update mb_mode set name = "chicken" where id = ' . $insertedMode;
		$this->db->query($query);
		
		$image1 = $this->insertImage();
		$this->assertTrue(isset($image1));
		
		$image2 = $this->insertImage();
		$this->assertTrue(isset($image2));
		
		$insertedSectionImage1 = $this->insertSectionImage($insertedID, $image1);
		$this->assertTrue(isset($insertedSectionImage1));
		
		$insertedSectionImage2 = $this->insertSectionImage($insertedID, $image2);
		$this->assertTrue(isset($insertedSectionImage2));
		
		$new = new MoodboardSection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->modeID = $insertedMode;
		$new->readExtra();
		$this->assertEquals(2, count($new->images));
		$this->assertEquals('chicken', $new->modeName);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedMode = $this->insertMode();
		$this->assertTrue(isset($insertedMode));
		
		$new = new MoodboardSection($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'hello';
		$new->description = 'Monkey';
		$new->modeID = $insertedMode;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['name']);
		$this->assertEquals('Monkey', $row['description']);
		$this->assertEquals($insertedMode, $row['modeID']);
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
		
		$new1 = new MoodboardSection($this->db, $insertedID1);
		$this->assertEquals($insertedID1, $new1->id);
		
		$new2 = new MoodboardSection($this->db, $insertedID2);
		$this->assertEquals($insertedID2, $new2->id);
		
		$new3 = new MoodboardSection($this->db, $insertedID3);
		$this->assertEquals($insertedID3, $new3->id);
		
		$new4 = new MoodboardSection($this->db, $insertedID4);
		$this->assertEquals($insertedID4, $new4->id);
		
		$new3->writePosition();
		$this->assertEquals(1, $new3->position);
		
		$new2->writePosition();
		$this->assertEquals(2, $new2->position);
		
		$new1->writePosition();
		$this->assertEquals(3, $new1->position);
		
		$new4->writePosition();
		$this->assertEquals(4, $new4->position);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set description = "Monkey" where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new MoodboardSection($this->db, $insertedID);
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
		
		$new = new MoodboardSection($this->db, $insertedID);
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
	
	private function insertMode()
	{
		$query = 'insert into mb_mode () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertImage()
	{
		$query = 'insert into mb_image () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertSectionImage($sectionID, $imageID)
	{
		$query = 'insert into mb_section_image (sectionID, imageID) values (' . $sectionID . ', ' . $imageID . ')';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>