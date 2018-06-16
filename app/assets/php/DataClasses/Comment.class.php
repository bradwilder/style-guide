<?php

class Comment extends DBItem
{
	public $text;
	public $postTime;
	public $commentReplyingToID;
	public $sectionImageID;
	public $userID;
	
	// Extra properties
	public $userName;
	
	private static $tableName = 'mb_comment';
	
	public function __construct(Db$db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->text, 'text', self::$tableName, true);
		
		if ($this->postTime)
		{
			$query = 'update ' . self::$tableName . ' set postTime = from_unixtime(?) where id = ?';
			$this->db->query($query, 'ii', array(&$this->postTime, &$this->id));
		}
		
		$this->writeBase($this->commentReplyingToID, 'commentReplyingToID', self::$tableName, false, true);
		$this->writeBase($this->sectionImageID, 'sectionImageID', self::$tableName, false, true);
		$this->writeBase($this->userID, 'userID', self::$tableName);
	}
	
	public function read()
	{
		$query = 'select text, unix_timestamp(postTime) as postTime, commentReplyingToID, sectionImageID, userID from ' . self::$tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		
		$this->setPropertiesFromRow($row);
	}
	
	public function readExtra()
	{
		$query = 'select case when displayName is not null and CHAR_LENGTH(displayName) > 0 then displayName else email end as user_name from users where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->userID))[0];
		
		$this->userName = $row['user_name'];
	}
	
	public function delete()
	{
		parent::deleteBase(self::$tableName);
	}
}

?>