<?php

class Attempt extends DBItem
{
	public $ip;
	public $expire;
	
	private static $tableName = 'attempts';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->ip, 'ip', true);
		
		if ($this->expire)
		{
			$query = 'update ' . self::$tableName . ' set expire = from_unixtime(?) where id = ?';
			$this->db->query($query, 'ii', array(&$this->expire, &$this->id));
		}
	}
	
	public function read()
	{
		$query = 'select ip, unix_timestamp(expire) as expire from ' . self::$tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		
		$this->setPropertiesFromRow($row);
	}
	
	public function delete()
	{
		parent::deleteBase();
	}
}

?>