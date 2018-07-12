<?php

class Session extends DBItem
{
	public $userID;
	public $hash;
	public $expire;
	public $ip;
	public $agent;
	public $cookieCRC;
	
	private static $tableName = 'sessions';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
	}
	
	public function write()
	{
		$this->writeBase($this->userID, 'userID');
		$this->writeBase($this->hash, 'hash', DBColumnType::String);
		
		if ($this->expire)
		{
			$query = 'update ' . self::$tableName . ' set expire = from_unixtime(?) where id = ?';
			$this->db->query($query, 'ii', array(&$this->expire, &$this->id));
		}
		
		$this->writeBase($this->ip, 'ip', DBColumnType::String);
		$this->writeBase($this->agent, 'agent', DBColumnType::String);
		$this->writeBase($this->cookieCRC, 'cookieCRC', DBColumnType::String);
	}
	
	public function read()
	{
		$query = 'select userID, hash, unix_timestamp(expire) as expire, ip, agent, cookieCRC from ' . self::$tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		
		$this->setPropertiesFromRow($row);
	}
}

?>