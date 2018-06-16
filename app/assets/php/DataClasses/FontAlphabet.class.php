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
		$this->writeBase($this->name, 'name', self::$tableName, true);
		$this->writeBase($this->alphabet, 'alphabet', self::$tableName, true);
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