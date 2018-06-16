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
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->userID, 'userID', self::$tableName);
		$this->writeBase($this->hash, 'hash', self::$tableName, true);
		
		if ($this->expire)
		{
			$query = 'update ' . self::$tableName . ' set expire = from_unixtime(?) where id = ?';
			$this->db->query($query, 'ii', array(&$this->expire, &$this->id));
		}
		
		$this->writeBase($this->ip, 'ip', self::$tableName, true);
		$this->writeBase($this->agent, 'agent', self::$tableName, true);
		$this->writeBase($this->cookieCRC, 'cookieCRC', self::$tableName, true);
	}
	
	public function read()
	{
		$query = 'select userID, hash, unix_timestamp(expire) as expire, ip, agent, cookieCRC from ' . self::$tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		
		$this->setPropertiesFromRow($row);
	}
	
	public function delete()
	{
		parent::deleteBase(self::$tableName);
	}
}

?>