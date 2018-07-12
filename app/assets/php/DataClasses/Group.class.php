<?php

class Group extends DBItem
{
	public $name;
	public $description;
	
	private static $tableName = 'groups';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('description', new DBColumn(DBColumnType::String, true));
	}
}

?>