<?php

class FontType extends DBItem
{
	public $code;
	public $description;
	
	private static $tableName = 'sg_font_type';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('code', new DBColumn(DBColumnType::String));
		$this->addColumn('description', new DBColumn(DBColumnType::String));
	}
}

?>