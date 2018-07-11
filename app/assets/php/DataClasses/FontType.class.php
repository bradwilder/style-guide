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
		$this->writeBase($this->code, 'code', true);
		$this->writeBase($this->description, 'description', true);
	}
}

?>