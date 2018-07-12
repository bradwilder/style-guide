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
	
	public function testPosition()
    {
		$new1 = new MoodboardSection($this->db);
		$this->assertNotNull($new1->id);
		
		$new2 = new MoodboardSection($this->db);
		$this->assertNotNull($new2->id);
		
		$new3 = new MoodboardSection($this->db);
		$this->assertNotNull($new3->id);
		
		$new4 = new MoodboardSection($this->db);
		$this->assertNotNull($new4->id);
		
		$copy1 = new MoodboardSection($this->db, $new1->id);
		$copy1->read();
		$this->assertEquals(1, $copy1->position);
		
		$copy2 = new MoodboardSection($this->db, $new2->id);
		$copy2->read();
		$this->assertEquals(2, $copy2->position);
		
		$copy3 = new MoodboardSection($this->db, $new3->id);
		$copy3->read();
		$this->assertEquals(3, $copy3->position);
		
		$copy4 = new MoodboardSection($this->db, $new4->id);
		$copy4->read();
		$this->assertEquals(4, $copy4->position);
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