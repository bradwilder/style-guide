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
		
		$this->addColumn('userID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('emailKey', new DBColumn(DBColumnType::String));
		$this->addColumn('smsKey', new DBColumn(DBColumnType::String));
		$this->addColumn('expire', new DBColumn(DBColumnType::Date));
		$this->addColumn('type', new DBColumn(DBColumnType::String));
	}
}

?>