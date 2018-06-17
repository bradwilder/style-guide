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
require_once __ASSETS_PATH . '/php/DataClasses/StyleguideFontTableListingCSS.class.php';

final class StyleguideFontTableListingCSSTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_font_listing_css';
	
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
		$this->db->query('delete from sg_font_listing');
	}
	
    public function testConstructorEmpty()
    {
		$new = new StyleguideFontTableListingCSS($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new StyleguideFontTableListingCSS($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new StyleguideFontTableListingCSS($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedListing = $this->insertFontTableListing();
		$this->assertTrue(isset($insertedListing));
		
		$query = 'update ' . self::$tableName . ' set css = "Monkey", fontListingID = ' . $insertedListing . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new StyleguideFontTableListingCSS($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('Monkey', $new->css);
		$this->assertEquals($insertedListing, $new->fontListingID);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedListing = $this->insertFontTableListing();
		$this->assertTrue(isset($insertedListing));
		
		$new = new StyleguideFontTableListingCSS($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->css = 'Monkey';
		$new->fontListingID = $insertedListing;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('Monkey', $row['css']);
		$this->assertEquals($insertedListing, $row['fontListingID']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new StyleguideFontTableListingCSS($this->db, $insertedID);
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
	
	private function insertFontTableListing()
	{
		$query = 'insert into sg_font_listing () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>