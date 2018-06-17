<?php

class MoodboardSectionImageSize extends DBItem
{
	public $name;
	public $code;
	public $description;
	
	private static $tableName = 'mb_size';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
	}
	
	public function write()
	{
		$this->writeBase($this->name, 'name', true);
		$this->writeBase($this->code, 'code', true);
		$this->writeBase($this->description, 'description', true, true);
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