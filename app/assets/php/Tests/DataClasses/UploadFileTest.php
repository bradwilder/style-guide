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
require_once __ASSETS_PATH . '/php/DataClasses/UploadFile.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/Upload.class.php';

final class UploadFileTest extends TestCase
{
	private $db;
	private static $tableName = 'sg_upload_file';
	
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
		$this->db->query('delete from sg_upload');
		$this->db->query('delete from sg_upload_type');
		$this->db->query('delete from sg_item');
		$this->db->query('delete from sg_element');
	}
	
    public function testConstructorNoID()
    {
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "file" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$new = new UploadFile($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedUploadType, $new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['baseID']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "file" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$new = new UploadFile($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "file" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$insertedUpload = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload));
		
		$this->insert($insertedUpload);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new UploadFile($this->db, $insertedUpload);
		$this->assertEquals($insertedUpload, $new->id);
	}
	
	public function testRead()
    {
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "file" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$insertedUpload = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload));
		
		$this->insert($insertedUpload);
		$this->assertEquals(1, $this->getTableCount());
		
		$query = 'update ' . self::$tableName . ' set shortName = "dragon", fullName = "helicopter"  where baseID = ' . $insertedUpload;
		$this->db->query($query);
		
		$new = new UploadFile($this->db, $insertedUpload);
		$this->assertEquals($insertedUpload, $new->id);
		
		$new->read();
		$this->assertEquals('dragon', $new->shortName);
		$this->assertEquals('helicopter', $new->fullName);
	}
	
	public function testWrite()
    {
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "file" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$insertedUpload = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload));
		
		$this->insert($insertedUpload);
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new UploadFile($this->db, $insertedUpload);
		$this->assertEquals($insertedUpload, $new->id);
		
		$new->shortName = 'dragon';
		$new->fullName = 'helicopter';
		$new->write();
		$row = $this->getByID($insertedUpload);
		$this->assertEquals('dragon', $row['shortName']);
		$this->assertEquals('helicopter', $row['fullName']);
	}
	
	public function testDelete()
    {
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "file" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$insertedUpload = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload));
		
		$this->insert($insertedUpload);
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedItem = $this->insertItem();
		$this->assertTrue(isset($insertedItem));
		
		$this->insertElementItem($insertedItem);
		$query = 'update sg_element set upload1ID = ' . $insertedUpload . ' where baseID = ' . $insertedItem;
		$this->db->query($query);
		
		$new = new UploadFile($this->db, $insertedUpload);
		$this->assertEquals($insertedUpload, $new->id);
		
		$new->delete();
		$this->assertEquals(0, $this->getTableCount());
		$this->assertEquals(0, $this->getTableCount('sg_item'));
		$this->assertEquals(0, $this->getTableCount('sg_element'));
	}
	
	private function getTableCount($table = null)
	{
		$query = 'select count(*) as count from ' . ($table ? $table : self::$tableName);
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
	
	private function insertUploadType()
	{
		$query = 'insert into sg_upload_type () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertUpload()
	{
		$query = 'insert into sg_upload () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertItem()
	{
		$query = 'insert into sg_item () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertElementItem($itemID)
	{
		$query = 'insert into sg_element (baseID) values (' . $itemID . ')';
		$this->db->query($query);
		$this->db->insert_id();
	}
}

?>