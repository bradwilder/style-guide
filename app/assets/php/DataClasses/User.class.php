<?php

class User extends DBItem
{
	public $displayName;
	public $email;
	public $phone;
	public $password;
	public $isActive;
	public $isDeleted;
	public $resetNeeded;
	public $groupID;
	
	// Extra properties
	public $sessions;
	public $requests;
	
	private static $tableName = 'users';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('displayName', new DBColumn(DBColumnType::String, true));
		$this->addColumn('email', new DBColumn(DBColumnType::String));
		$this->addColumn('phone', new DBColumn(DBColumnType::String, true));
		$this->addColumn('password', new DBColumn(DBColumnType::String));
		$this->addColumn('isActive', new DBColumn(DBColumnType::Boolean));
		$this->addColumn('isDeleted', new DBColumn(DBColumnType::Boolean));
		$this->addColumn('resetNeeded', new DBColumn(DBColumnType::Boolean));
		$this->addColumn('groupID', new DBColumn(DBColumnType::Numeric));
	}
	
	public function readExtra()
	{
		$query = 'select count(*) as session_count from sessions where userID = ?';
		$this->sessions = $this->db->select($query, 'i', [&$this->id])[0]['session_count'];
		
		$query = 'select dt.expire from (select userID, expire from requests group by userID, emailKey, expire) dt where dt.userID = ?';
		$rows = $this->db->select($query, 'i', [&$this->id]);
		
		$this->requests = [];
		foreach ($rows as $row)
		{
			$this->requests []= $row['expire'];
		}
	}
	
	public static function nameExists(Db $db, string $name, int $selfID = null)
	{
		$query = 'select count(*) as count from ' . self::$tableName . ' where email = ?';
		$types = 's';
		$params = [&$name];
		if ($selfID)
		{
			$query .= ' and id <> ?';
			$types .= 'i';
			$params []= &$selfID;
		}
		
		$rows = $db->select($query, $types, $params);
		return ($rows[0]['count'] != 0);
	}
	
	public static function getUserName(DB $db, int $userID)
	{
		$query = 'select case when displayName is not null and CHAR_LENGTH(displayName) > 0 then displayName else email end as user_name from ' . self::$tableName . ' where id = ?';
		$row = $db->select($query, 'i', [&$userID])[0];
		
		return $row['user_name'];
	}
}

?>