<?php

class FontType extends DBItem
{
	public $code;
	public $description;
	
	private static $tableName = 'sg_font_type';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
	}
	
	public function write()
	{
		$this->writeBase($this->code, 'code', DBColumnType::String);
		$this->writeBase($this->description, 'description', DBColumnType::String);
	}
}

?>