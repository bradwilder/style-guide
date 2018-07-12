<?php

class Attempt extends DBItem
{
	public $ip;
	public $expire;
	
	private static $tableName = 'attempts';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('ip', new DBColumn(DBColumnType::String));
		$this->addColumn('expire', new DBColumn(DBColumnType::Date));
	}
}

?>