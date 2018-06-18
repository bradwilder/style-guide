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
require_once __ASSETS_PATH . '/php/DataClasses/UploadFolder.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/Upload.class.php';

final class UploadFolderTest extends TestCase
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
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "folder" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$new = new UploadFolder($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals($insertedUploadType, $new->typeID);
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "folder" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$new = new Upload($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "folder" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$query = 'update sg_upload set typeID = ' . $insertedUploadType . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new UploadFolder($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testGetChildren()
	{
		$insertedUploadType = $this->insertUploadType();
		$this->assertTrue(isset($insertedUploadType));
		$query = 'update sg_upload_type set code = "folder" where id = ' . $insertedUploadType;
		$this->db->query($query);
		
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$query = 'update sg_upload set typeID = ' . $insertedUploadType . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$insertedUpload1 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload1));
		$query = 'update sg_upload set typeID = ' . $insertedUploadType . ', parentID = ' . $insertedID . ' where id = ' . $insertedUpload1;
		$this->db->query($query);
		
		$insertedUpload2 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload2));
		$query = 'update sg_upload set typeID = ' . $insertedUploadType . ', parentID = ' . $insertedID . ' where id = ' . $insertedUpload2;
		$this->db->query($query);
		
		$insertedUpload3 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload3));
		$query = 'update sg_upload set typeID = ' . $insertedUploadType . ', parentID = ' . $insertedID . ' where id = ' . $insertedUpload3;
		$this->db->query($query);
		
		$insertedUpload4 = $this->insertUpload();
		$this->assertTrue(isset($insertedUpload4));
		$query = 'update sg_upload set typeID = ' . $insertedUploadType . ', parentID = ' . $insertedID . ' where id = ' . $insertedUpload4;
		$this->db->query($query);
		
		$new = new UploadFolder($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		$children = $new->getChildren();
		$this->assertEquals(4, count($children));
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
	
	private function insertUpload()
	{
		$query = 'insert into sg_upload () values ()';
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