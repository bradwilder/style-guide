<?php

class Requests extends DBItem
{
	public $userID;
	public $emailKey;
	public $smsKey;
	public $expire;
	public $type;
	
	private static $tableName = 'requests';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
	}
	
	public function write()
	{
		$this->writeBase($this->userID, 'userID');
		$this->writeBase($this->emailKey, 'emailKey', true);
		$this->writeBase($this->smsKey, 'smsKey', true);
		
		if ($this->expire)
		{
			$query = 'update ' . self::$tableName . ' set expire = from_unixtime(?) where id = ?';
			$this->db->query($query, 'ii', array(&$this->expire, &$this->id));
		}
		
		$this->writeBase($this->type, 'type', true);
	}
	
	public function read()
	{
		$query = 'select userID, emailKey, smsKey, unix_timestamp(expire) as expire, type from ' . self::$tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		
		$this->setPropertiesFromRow($row);
	}
	
	public function delete()
	{
		parent::deleteBase();
	}
}

?>