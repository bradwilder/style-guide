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
require_once __ASSETS_PATH . '/php/DataClasses/Comment.class.php';

final class CommentTest extends TestCase
{
	private $db;
	private static $tableName = 'mb_comment';
	
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
		$this->db->query('delete from mb_section_image');
		$this->db->query('delete from users');
	}
	
    public function testConstructorEmpty()
    {
		$new = new Comment($this->db);
		$this->assertTrue(isset($new->id));
		$this->assertEquals(1, $this->getTableCount());
		
		$row = $this->getByID($new->id);
		$this->assertEquals($new->id, $row['id']);
	}
	
	public function testConstructorIDNoMatch()
    {
		$new = new Comment($this->db, 6);
		$this->assertEquals(0, $this->getTableCount());
	}
	
	public function testConstructorIDMatch()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		$new = new Comment($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
	}
	
	public function testRead()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUser = $this->insertUser();
		$this->assertTrue(isset($insertedUser));
		
		$insertedSectionImage = $this->insertSectionImage();
		$this->assertTrue(isset($insertedSectionImage));
		
		$query = 'update ' . self::$tableName . ' set text = "hello", postTime = "2018-01-14 20:26:40", commentReplyingToID = ' . $insertedID . ', sectionImageID = ' . $insertedSectionImage . ', userID = ' . $insertedUser . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Comment($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		$this->assertEquals('hello', $new->text);
		$this->assertEquals(1515986800, $new->postTime);
		$this->assertEquals($new->commentReplyingToID, $insertedID);
		$this->assertEquals($new->sectionImageID, $insertedSectionImage);
		$this->assertEquals($new->userID, $insertedUser);
	}
	
	public function testReadExtra()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUser = $this->insertUser();
		$this->assertTrue(isset($insertedUser));
		$query = 'update users set displayName = "derp" where id = ' . $insertedUser;
		$this->db->query($query);
		
		$new = new Comment($this->db, $insertedID);
		$new->userID = $insertedUser;
		$this->assertEquals($insertedID, $new->id);
		$new->readExtra();
		$this->assertEquals('derp', $new->userName);
	}
	
	public function testWrite()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedUser = $this->insertUser();
		$this->assertTrue(isset($insertedUser));
		
		$insertedSectionImage = $this->insertSectionImage();
		$this->assertTrue(isset($insertedSectionImage));
		
		$new = new Comment($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->text = 'hello';
		$new->postTime = 1515986800;
		$new->commentReplyingToID = $insertedID;
		$new->sectionImageID = $insertedSectionImage;
		$new->userID = $insertedUser;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertEquals('hello', $row['text']);
		$this->assertEquals('2018-01-14 20:26:40', $row['postTime']);
		$this->assertEquals($insertedID, $row['commentReplyingToID']);
		$this->assertEquals($insertedSectionImage, $row['sectionImageID']);
		$this->assertEquals($insertedUser, $row['userID']);
	}
	
	public function testWriteNull()
	{
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$insertedSectionImage = $this->insertSectionImage();
		$this->assertTrue(isset($insertedSectionImage));
		
		$query = 'update ' . self::$tableName . ' set sectionImageID = ' . $insertedSectionImage . ', commentReplyingToID = ' . $insertedID . ' where id = ' . $insertedID;
		$this->db->query($query);
		
		$new = new Comment($this->db, $insertedID);
		$this->assertEquals($insertedID, $new->id);
		
		$new->read();
		
		$new->sectionImageID = 0;
		$new->commentReplyingToID = 0;
		$new->write();
		$row = $this->getByID($insertedID);
		$this->assertNull($row['sectionImageID']);
		$this->assertNull($row['commentReplyingToID']);
	}
	
	public function testDelete()
    {
		$insertedID = $this->insert();
		$this->assertEquals(1, $this->getTableCount());
		
		$new = new Comment($this->db, $insertedID);
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
	
	private function insertSectionImage()
	{
		$query = 'insert into mb_section_image () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	private function insertUser()
	{
		$query = 'insert into users () values ()';
		$this->db->query($query);
		return $this->db->insert_id();
	}
}

?>