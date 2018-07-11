<?php

class Group extends DBItem
{
	public $name;
	public $description;
	
	private static $tableName = 'groups';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
	}
	
	public function write()
	{
		$this->writeBase($this->name, 'name', true);
		$this->writeBase($this->description, 'description', true, true);
	}
}

?>