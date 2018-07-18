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
		$this->userName = User::getUserName($this->db, $this->userID);
	}
}

?>