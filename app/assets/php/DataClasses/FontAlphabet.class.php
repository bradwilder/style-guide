<?php

class FontAlphabet extends DBItem
{
	public $name;
	public $alphabet;
	
	private static $tableName = 'sg_font_alphabet';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->name, 'name', true);
		$this->writeBase($this->alphabet, 'alphabet', true);
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