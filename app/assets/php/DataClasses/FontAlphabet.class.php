<?php

class FontAlphabet extends DBItem
{
	public $name;
	public $code;
	public $alphabet;
	
	private static $tableName = 'sg_font_alphabet';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('code', new DBColumn(DBColumnType::String));
		$this->addColumn('alphabet', new DBColumn(DBColumnType::String));
	}
}

?>