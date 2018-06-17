<?php

class Group extends DBItem
{
	public $name;
	public $description;
	
	private static $tableName = 'groups';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->name, 'name', self::$tableName, true);
		$this->writeBase($this->description, 'description', self::$tableName, true, true);
	}
	
	public function read()
	{
		parent::readBase();
	}
	
	public function delete()
	{
		parent::deleteBase();
	}
}

?>