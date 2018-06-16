<?php

class MoodboardSectionImageSize extends DBItem
{
	public $name;
	public $code;
	public $description;
	
	private static $tableName = 'mb_size';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->name, 'name', self::$tableName, true);
		$this->writeBase($this->code, 'code', self::$tableName, true);
		$this->writeBase($this->description, 'description', self::$tableName, true, true);
	}
	
	public function read()
	{
		parent::readBase(self::$tableName);
	}
	
	public function delete()
	{
		parent::deleteBase(self::$tableName);
	}
}

?>