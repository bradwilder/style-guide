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
require_once __ASSETS_PATH . '/php/DataClasses/WebFont.class.php';

final class WebFontTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_webfont';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->db = new Db('test');
	}
	
	/**
     * @before
	 * @after
     */
	public function clear()
	{
		$this->db->query('delete from ' . self::$tableName);
		$this->db->query('delete from sg_font_type');
		$this->db->query('delete from sg_font');
	}
	
    public function testConstructorNoID()
    {
		$insertedFontType = $this->insertFontType();
		$this->assertTrue(isset($insertedFontType));
		$query = 'update sg_font_type set code = "web" where id = ' . $insertedFontType;
		$this->db->query($query);
		
		$new = new WebFont($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedFontType, $new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['baseID']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$insertedFontType = $this->insertFontType();
		$this->assertTrue(isset($insertedFontType));
		$query = 'update sg_font_type set code = "web" where id = ' . $insertedFontType;
		$this->db->query($query);
		
		$new = new WebFont($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedFontType = $this->insertFontType();
		$this->assertTrue(isset($insertedFontType));
		$query = 'update sg_font_type set code = "web" where id = ' . $insertedFontType;
		$this->db->query($query);
		
		$insertedFont = $this->insertFont();
		$this->assertTrue(isset($insertedFont));
		
		$this->insert($insertedFont);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new WebFont($this->db, $insertedFont);
		$this->assertEquals($insertedFont, $new->id);
	}
	
	public function testRead()
    {
		$insertedFontType = $this->insertFontType();
		$this->assertTrue(isset($insertedFontType));
		$query = 'update sg_font_type set code = "web" where id = ' . $insertedFontType;
		$this->db->query($query);
		
		$insertedFont = $this->insertFont();
		$this->assertTrue(isset($insertedFont));
		
		$this->insert($insertedFont);
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set importURL = "pineapple", website = "haystack" where baseID = ' . $insertedFont;
		$this->db->query($query);
		
		$new = new WebFont($this->db, $insertedFont);
		$this->assertEquals($insertedFont, $new->id);
		
		$new->read();
		$this->assertEquals('pineapple', $new->importURL);
		$this->assertEquals('haystack', $new->website);
	}
	
	public function testWrite()
    {
		$insertedFontType = $this->insertFontType();
		$this->assertTrue(isset($insertedFontType));
		$query = 'update sg_font_type set code = "web" where id = ' . $insertedFontType;
		$this->db->query($query);
		
		$insertedFont = $this->insertFont();
		$this->assertTrue(isset($insertedFont));
		
		$this->insert($insertedFont);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new WebFont($this->db, $insertedFont);
		$this->assertEquals($insertedFont, $new->id);
		
		$new->importURL = 'pineapple';
		$new->website = 'haystack';
		$new->write();
		$row = $this->getByID($insertedFont);
		$this->assertEquals('pineapple', $row['importURL']);
		$this->assertEquals('haystack', $row['website']);
	}
	
	public function testWriteNull()
	{
		$insertedFontType = $this->insertFontType();
		$this->assertTrue(isset($insertedFontType));
		$query = 'update sg_font_type set code = "web" where id = ' . $insertedFontType;
		$this->db->query($query);
		
		$insertedFont = $this->insertFont();
		$this->assertTrue(isset($insertedFont));
		
		$this->insert($insertedFont);
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set website = "pineapple" where baseID = ' . $insertedFont;
		$this->db->query($query);
		
		$new = new WebFont($this->db, $insertedFont);
		$this->assertEquals($insertedFont, $new->id);
		
		$new->read();
		$new->website = '';
		$new->write();
		$row = $this->getByID($insertedFont);
		$this->assertNull($row['website']);
	}
	
	public function testDelete()
    {
		$insertedFontType = $this->insertFontType();
		$this->assertTrue(isset($insertedFontType));
		$query = 'update sg_font_type set code = "web" where id = ' . $insertedFontType;
		$this->db->query($query);
		
		$insertedFont = $this->insertFont();
		$this->assertTrue(isset($insertedFont));
		
		$this->insert($insertedFont);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new WebFont($this->db, $insertedFont);
		$this->assertEquals($insertedFont, $new->id);
		
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
		$query = 'select * from ' . self::$tableName . ' where baseID = ' . $id;
		$rows = $this->db->select($query);
		return $rows[0];
	}
	
	private function insert($id)
	{
		$query = 'insert into ' . self::$tableName . ' (baseID) values (' . $id . ')';
		$this->db->query($query);
	}
	
	private function insertFont()
	{
		$query = 'insert into sg_font () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertFontType()
	{
		$query = 'insert into sg_font_type () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>