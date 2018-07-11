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
require_once __ASSETS_PATH . '/php/DataClasses/Font.class.php';
//require_once __ASSETS_PATH . '/php/DataClasses/StyleguideItemType.class.php';

final class FontTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_font';
	
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
		$this->db->query('delete from sg_font_type');
		$this->db->query('delete from sg_webfont');
		$this->db->query('delete from sg_font_alphabet');
		$this->db->query('delete from sg_item');
		$this->db->query('delete from sg_font_family');
	}
	
    public function testConstructorNoID()
    {
		// No id, code, or sub table
		$new = new Font($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertNull($new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
		
		// No id, with code and sub table
		$insertedItemType = $this->insertFontType();
		$this->assertTrue(isset($insertedItemType));
		$query = 'update sg_font_type set code = "web" where id = ' . $insertedItemType;
		$this->db->query($query);
		
		$new = new Font($this->db, null, 'web', 'sg_webfont');
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedItemType, $new->typeID);
		$this->assertEquals(2, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
		$this->assertEquals($new->typeID, $row['typeID']);
		$this->assertEquals($new->typeID, $insertedItemType);
		
		$query = 'select count(*) as count from sg_webfont';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
		
		// No id, with code but no sub table
		$new = new Font($this->db, null, 'web');
		$this->assertTrue(!isset($new->id));
		$this->assertEquals(2, $this->getTableCount());
		
		$query = 'select count(*) as count from sg_webfont';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
		
		// No id or code, but with sub table
		$new = new Font($this->db, null, null, 'sg_webfont');
		$this->assertTrue(!isset($new->id));
		$this->assertEquals(2, $this->getTableCount());
		
		$query = 'select count(*) as count from sg_webfont';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new Font($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new Font($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedAlphabet = $this->insertFontAlphabet();
		$this->assertTrue(isset($insertedAlphabet));
		
		$query = 'update ' . self::$tableName . ' set name = "Dinosaur", alphabetID = ' . $insertedAlphabet . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Font($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('Dinosaur', $new->name);
		$this->assertEquals($insertedAlphabet, $new->alphabetID);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedFontType = $this->insertFontType();
		$this->assertTrue(isset($insertedFontType));
		
		$insertedAlphabet = $this->insertFontAlphabet();
		$this->assertTrue(isset($insertedAlphabet));
		
		$new = new Font($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->typeID = $insertedFontType;
		$new->alphabetID = $insertedAlphabet;
		$new->readExtra();
		$this->assertEquals($insertedFontType, $new->type->id);
		$this->assertEquals($insertedAlphabet, $new->alphabet->id);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedAlphabet = $this->insertFontAlphabet();
		$this->assertTrue(isset($insertedAlphabet));
		
		$new = new Font($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->name = 'Dinosaur';
		$new->alphabetID = $insertedAlphabet;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('Dinosaur', $row['name']);
		$this->assertEquals($insertedAlphabet, $row['alphabetID']);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedAlphabet = $this->insertFontAlphabet();
		$this->assertTrue(isset($insertedAlphabet));
		
		$query = 'update ' . self::$tableName . ' set alphabetID = ' . $insertedAlphabet . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Font($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$new->alphabetID = 0;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertNull($row['alphabetID']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insertFontFamilyItem($insertedItem, $insertedID);
		
		$new = new Font($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->delete();
		$this->assertEquals(0, $this->getTableCount());
		$this->assertEquals(0, $this->getTableCount('sg_item'));
		$this->assertEquals(0, $this->getTableCount('sg_font_family'));
	}
	
	private function getTableCount($table = null)
	{
		$query = 'select count(*) as count from ' . ($table ? $table : self::$tableName);
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
	
	private function insertFontAlphabet()
	{
		$query = 'insert into sg_font_alphabet () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertFontType()
	{
		$query = 'insert into sg_font_type () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertItem()
	{
		$query = 'insert into sg_item () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertFontFamilyItem($itemID, $fontID)
	{
		$query = 'insert into sg_font_family (baseID, fontID) values (' . $itemID . ', ' . $fontID . ')';
		$this->db->query($query);
	}
}

?>