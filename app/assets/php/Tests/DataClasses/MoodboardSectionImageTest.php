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
require_once __ASSETS_PATH . '/php/DataClasses/MoodboardSectionImage.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/MoodboardSectionImageSize.class.php';

final class MoodboardSectionImageTest extends TestCase
{
	private $db;
	private static $tableName = 'mb_section_image';
	
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
		$this->db->query('delete from mb_image');
		$this->db->query('delete from mb_size');
	}
	
    public function testConstructorEmpty()
    {
		$new = new MoodboardSectionImage($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new MoodboardSectionImage($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new MoodboardSectionImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSection = $this->insertSection();
		$this->assertTrue(isset($insertedSection));
		
		$insertedImage = $this->insertImage();
		$this->assertTrue(isset($insertedImage));
		
		$insertedSize = $this->insertSize();
		$this->assertTrue(isset($insertedSize));
		
		$query = 'update ' . self::$tableName . ' set sectionID = ' . $insertedSection . ', imageID = ' . $insertedImage . ', sizeID = ' . $insertedSize . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new MoodboardSectionImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals($insertedSection, $new->sectionID);
		$this->assertEquals($insertedImage, $new->imageID);
		$this->assertEquals($insertedSize, $new->sizeID);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSize = $this->insertSize();
		$this->assertTrue(isset($insertedSize));
		
		$new = new MoodboardSectionImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->sizeID = $insertedSize;
		$new->readExtra();
		$this->assertEquals($insertedSize, $new->size->id);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSection = $this->insertSection();
		$this->assertTrue(isset($insertedSection));
		
		$insertedImage = $this->insertImage();
		$this->assertTrue(isset($insertedImage));
		
		$insertedSize = $this->insertSize();
		$this->assertTrue(isset($insertedSize));
		
		$new = new MoodboardSectionImage($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->sectionID = $insertedSection;
		$new->imageID = $insertedImage;
		$new->sizeID = $insertedSize;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals($insertedSection, $row['sectionID']);
		$this->assertEquals($insertedImage, $row['imageID']);
		$this->assertEquals($insertedSize, $row['sizeID']);
	}
	
	public function testPosition()
    {
		$insertedSection1 = $this->insertSection();
		$this->assertTrue(isset($insertedSection1));
		
		$insertedSection2 = $this->insertSection();
		$this->assertTrue(isset($insertedSection2));
		
		$new1 = new MoodboardSectionImage($this->db, null, $insertedSection1);
		$this->assertNotNull($new1->id);
		
		$new2 = new MoodboardSectionImage($this->db, null, $insertedSection2);
		$this->assertNotNull($new2->id);
		
		$new3 = new MoodboardSectionImage($this->db, null, $insertedSection2);
		$this->assertNotNull($new3->id);
		
		$new4 = new MoodboardSectionImage($this->db, null, $insertedSection1);
		$this->assertNotNull($new4->id);
		
		$copy1 = new MoodboardSectionImage($this->db, $new1->id);
		$copy1->read();
		$this->assertEquals(1, $copy1->position);
		$this->assertEquals($insertedSection1, $copy1->sectionID);
		
		$copy2 = new MoodboardSectionImage($this->db, $new2->id);
		$copy2->read();
		$this->assertEquals(1, $copy2->position);
		$this->assertEquals($insertedSection2, $copy2->sectionID);
		
		$copy3 = new MoodboardSectionImage($this->db, $new3->id);
		$copy3->read();
		$this->assertEquals(2, $copy3->position);
		$this->assertEquals($insertedSection2, $copy3->sectionID);
		
		$copy4 = new MoodboardSectionImage($this->db, $new4->id);
		$copy4->read();
		$this->assertEquals(2, $copy4->position);
		$this->assertEquals($insertedSection1, $copy4->sectionID);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new MoodboardSectionImage($this->db, $insertedID);
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
		$query = 'insert into mb_section () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertImage()
	{
		$query = 'insert into mb_image () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertSize()
	{
		$query = 'insert into mb_size () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>