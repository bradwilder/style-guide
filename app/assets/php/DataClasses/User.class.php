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
	}
	
	public function write()
	{
		$this->writeBase($this->displayName, 'displayName', DBColumnType::String, true);
		$this->writeBase($this->email, 'email', DBColumnType::String);
		$this->writeBase($this->phone, 'phone', DBColumnType::String, true);
		$this->writeBase($this->password, 'password', DBColumnType::String);
		$this->writeBase($this->isActive, 'isActive', DBColumnType::Boolean);
		$this->writeBase($this->isDeleted, 'isDeleted', DBColumnType::Boolean);
		$this->writeBase($this->resetNeeded, 'resetNeeded', DBColumnType::Boolean);
		$this->writeBase($this->groupID, 'groupID');
	}
	
	public function read()
	{
		$query = 'select * from ' . self::$tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		
		$this->displayName = $row['displayName'];
		$this->email = $row['email'];
		$this->phone = $row['phone'];
		$this->password = $row['password'];
		$this->isActive = ($row['isActive'] == 1);
		$this->isDeleted = ($row['isDeleted'] == 1);
		$this->resetNeeded = ($row['resetNeeded'] == 1);
		$this->groupID = $row['groupID'];
	}
	
	public function readExtra()
	{
		$query = 'select count(*) as session_count from sessions where userID = ?';
		$this->sessions = $this->db->select($query, 'i', array(&$this->id))[0]['session_count'];
		
		$query = 'select dt.expire from (select userID, expire from requests group by userID, emailKey, expire) dt where dt.userID = ?';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		
		$this->requests = array();
		foreach ($rows as $row)
		{
			$this->requests []= $row['expire'];
		}
	}
	
	public static function nameExists(string $name, int $selfID = null)
	{
		$db = new Db();
		
		$query = 'select count(*) as count from ' . self::$tableName . ' where email = ?';
		$types = 's';
		$params = array(&$name);
		if ($selfID)
		{
			$query .= ' and id <> ?';
			$types .= 'i';
			$params []= &$selfID;
		}
		
		$rows = $db->select($query, $types, $params);
		return ($rows[0]['count'] != 0);
	}
}

?>