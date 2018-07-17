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
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('text', new DBColumn(DBColumnType::String));
		$this->addColumn('postTime', new DBColumn(DBColumnType::Date));
		$this->addColumn('commentReplyingToID', new DBColumn(DBColumnType::Numeric, true));
		$this->addColumn('sectionImageID', new DBColumn(DBColumnType::Numeric, true));
		$this->addColumn('userID', new DBColumn(DBColumnType::Numeric));
	}
	
	public function readExtra()
	{
		$query = 'select case when displayName is not null and CHAR_LENGTH(displayName) > 0 then displayName else email end as user_name from users where id = ?';
		$row = $this->db->select($query, 'i', [&$this->userID])[0];
		
		$this->userName = $row['user_name'];
	}
}

?>