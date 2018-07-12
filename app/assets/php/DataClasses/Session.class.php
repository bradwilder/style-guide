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
		
		$this->addColumn('userID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('hash', new DBColumn(DBColumnType::String));
		$this->addColumn('expire', new DBColumn(DBColumnType::Date));
		$this->addColumn('ip', new DBColumn(DBColumnType::String));
		$this->addColumn('agent', new DBColumn(DBColumnType::String));
		$this->addColumn('cookieCRC', new DBColumn(DBColumnType::String));
	}
}

?>