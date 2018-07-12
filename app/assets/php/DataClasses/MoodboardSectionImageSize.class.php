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
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('code', new DBColumn(DBColumnType::String));
		$this->addColumn('description', new DBColumn(DBColumnType::String, true));
	}
}

?>