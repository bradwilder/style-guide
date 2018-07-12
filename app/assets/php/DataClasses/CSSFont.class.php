<?php

class CSSFont extends Font
{
	public $directory;
	public $cssFile;
	
	private static $tableName = 'sg_cssfont';
	public static $code = 'css';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$code, self::$tableName);
		
		$this->addSubColumn('directory', new DBColumn(DBColumnType::String, true));
		$this->addSubColumn('cssFile', new DBColumn(DBColumnType::String));
	}
	
	public function read()
	{
		parent::read(true);
	}
}

?>