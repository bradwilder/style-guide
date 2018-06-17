<?php

class FontType extends DBItem
{
	public $code;
	public $description;
	
	private static $tableName = 'sg_font_type';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->code, 'code', self::$tableName, true);
		$this->writeBase($this->description, 'description', self::$tableName, true);
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