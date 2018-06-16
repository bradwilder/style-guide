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
require_once __ASSETS_PATH . '/php/DataClasses/Upload.class.php';

final class UploadTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_upload';
	
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
		$this->db->query('delete from sg_upload_type');
		$this->db->query('delete from sg_upload_file');
	}
	
    public function testConstructorNoID()
    {
		// No id, code, or sub table
		$new = new Upload($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertNull($new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
		
		// No id, with code and sub table
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "boxcar" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$new = new Upload($this->db, null, 'boxcar', 'sg_upload_file');
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedUploadType, $new->typeID);
		$this->assertEquals(2, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
		$this->assertEquals($new->typeID, $row['typeID']);
		$this->assertEquals($new->typeID, $insertedUploadType);
		
		$query = 'select count(*) as count from sg_upload_file';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
		
		// No id, with code but no sub table
		$new = new Upload($this->db, null, 'boxcar');
		$this->assertTrue(isset($new->id));
		$this->assertEquals(3, $this->getTableCount());
		
		$query = 'select count(*) as count from sg_upload_file';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
		
		// No id or code, but with sub table
		$new = new Upload($this->db, null, null, 'sg_upload_file');
		$this->assertTrue(!isset($new->id));
		$this->assertEquals(3, $this->getTableCount());
		
		$query = 'select count(*) as count from sg_upload_file';
		$rows = $this->db->select($query);
		$count = $rows[0]['count'];
		$this->assertEquals(1, $count);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new Upload($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new Upload($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		
		$query = 'update ' . self::$tableName . ' set typeID = ' . $insertedUploadType . ', filePath = "asparagus", parentID = ' . $insertedID . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Upload($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals($insertedUploadType, $new->typeID);
		$this->assertEquals($insertedID, $new->parentID);
		$this->assertEquals('asparagus', $new->filePath);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItemType = $this->insertUploadType();
		$this->assertTrue(isset($insertedItemType));
		
		$new = new Upload($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$new->typeID = $insertedItemType;
		$new->readExtra();
		$this->assertEquals($insertedItemType, $new->type->id);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		
		$new = new Upload($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->typeID = $insertedUploadType;
		$new->parentID = $insertedID;
		$new->filePath = 'asparagus';
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals($insertedUploadType, $row['typeID']);
		$this->assertEquals($insertedID, $row['parentID']);
		$this->assertEquals('asparagus', $row['filePath']);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set parentID = ' . $insertedID . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Upload($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		
		$new->parentID = 0;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertNull($row['parentID']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new Upload($this->db, $insertedID);
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
	
	private function insertUploadType()
	{
		$query = 'insert into sg_upload_type () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>